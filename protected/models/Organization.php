<?php

/**
 * This is the model class for table "{{organization}}".
 *
 * The followings are the available columns in table '{{organization}}':
 * @property integer $id
 * @property integer $organization_type_id
 * @property integer $organization_group_id
 * @property integer $organization_region_id
 * @property string $organization_name
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Customer[] $customers
 * @property Deal[] $deals
 * @property OrganizationContact[] $contacts
 * @property OrganizationType $organization_type
 * @property OrganizationGroup $organization_group
 * @property OrganizationRegion $organization_region
 * @ property Users[] $tblUsers
 * @ property Users[] $fav_users
 */
class Organization extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Organization the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{organization}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('organization_type_id, organization_group_id, organization_region_id, value', 'required'),
            array('organization_type_id, organization_group_id, organization_region_id', 'numerical', 'integerOnly' => true),
            array('value, organization_name', 'length', 'max' => 255),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, organization_type_id, organization_group_id, organization_region_id, organization_name, value, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'contacts' => array(self::HAS_MANY, 'OrganizationContact', 'organization_id'),
            'customers' => array(self::HAS_MANY, 'Customer', 'organization_id'),
            'deals' => array(self::HAS_MANY, 'Deal', 'organization_id'),

            'organization_type' => array(self::BELONGS_TO, 'OrganizationType', 'organization_type_id'),
            'organization_group' => array(self::BELONGS_TO, 'OrganizationGroup', 'organization_group_id'),
            'organization_region' => array(self::BELONGS_TO, 'OrganizationRegion', 'organization_region_id'),
            //'tblUsers' => array(self::MANY_MANY, 'Users', '{{organization_fav}}(organization_id, user_id)'),
            //'fav_users' => array(self::HAS_MANY, 'OrganizationFav', 'id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('organization');
    }

    public function defaultScope()
    {
        return array(//'with'=> array('contacts', 'customers', 'deals')
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('organization_type', 'organization_group', 'organization_region');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('organization_type.value', $this->organization_type_id, true);
        $criteria->compare('organization_group.value', $this->organization_group_id, true);
        $criteria->compare('organization_region.value', $this->organization_region_id, true);
        $criteria->compare('t.organization_name', $this->organization_name, true);
        $criteria->compare('t.value', $this->value, true);
        $criteria->compare('t.description', $this->description, true);

        return $this->getByCriteria($criteria);
    }

    public function getAvailableAttributes()
    {
        return array('id', 'organization_type_id', 'organization_group_id', 'organization_region_id', 'value', 'organization_name', 'description');
    }

    public function getAll($userProfile, $select = '')
    {
        $criteria = new CDbCriteria;
        switch ($select) {
            case 'favorite':
                $criteria->join = 'LEFT JOIN {{organization_fav}} j ON j.id=t.id';
                $criteria->condition = 'j.user_id=:userid';
                $criteria->params = array(':userid' => Yii::app()->user->id);
                break;
        }
        if ($userProfile->filter_organization_type_id) {
            $criteria->addCondition('organization_type_id=:type');
            $criteria->params[':type'] = $userProfile->filter_organization_type_id;
        }
        if ($userProfile->filter_organization_group_id) {
            $criteria->addCondition('organization_group_id=:group');
            $criteria->params[':group'] = $userProfile->filter_organization_group_id;
        }
        if ($userProfile->filter_organization_region_id) {
            $criteria->addCondition('organization_region_id=:region');
            $criteria->params[':region'] = $userProfile->filter_organization_region_id;
        }
        return $this->getByCriteria($criteria, $userProfile->organization_pagesize);
    }

}