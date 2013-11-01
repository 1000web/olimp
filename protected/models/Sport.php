<?php

/**
 * This is the model class for table "{{sport}}".
 *
 * The followings are the available columns in table '{{sport}}':
 * @property integer $id
 * @property integer $sportgroup_id
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Users $update_user
 */
class Sport extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Sport the static model class
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
        return '{{sport}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('value', 'required'),
            array('value', 'length', 'max' => 255),
            array('sportgroup_id', 'numerical', 'integerOnly' => true),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sportgroup_id, value, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'sportgroup' => array(self::BELONGS_TO, 'Sportgroup', 'sportgroup_id'),
            //'organization_contacts' => array(self::HAS_MANY, 'OrganizationContact', 'sport_id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('sport');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'sportgroup_id', 'prior', 'visible', 'value', 'description');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id', $this->id);
        $criteria->compare('sportgroup', $this->sportgroup_id, true);
        $criteria->compare('t.value', $this->value, true);
        $criteria->compare('t.description', $this->description, true);

        return $this->getByCriteria($criteria);
    }

    public function getAll($pagesize = 20)
    {
        $criteria = new CDbCriteria;
        $criteria->order = 't.prior ASC';
        return $this->getByCriteria($criteria, $pagesize);
    }

    public function getVisible($pagesize = 20)
    {
        $criteria = new CDbCriteria;
        $criteria->order = 'sportgroup.prior ASC, t.prior ASC';
        $criteria->addCondition('sportgroup.visible=1');
        $criteria->addCondition('t.visible=1');
        $criteria->with = array('sportgroup');
        $criteria->limit = $pagesize;
        return $this->getByCriteria($criteria, $pagesize);
    }

}