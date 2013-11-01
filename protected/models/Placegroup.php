<?php

/**
 * This is the model class for table "{{placegroup}}".
 *
 * The followings are the available columns in table '{{placegroup}}':
 * @property integer $id
 * @property integer $prior
 * @property string $url
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 */
class Placegroup extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Placegroup the static model class
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
        return '{{placegroup}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('value', 'required'),
            array('value, url', 'length', 'max' => 255),
            array('description', 'safe'),
            array('prior', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, prior, url, value, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //'customer_contacts' => array(self::HAS_MANY, 'CustomerContact', 'placegroup_id'),
            //'organization_contacts' => array(self::HAS_MANY, 'OrganizationContact', 'placegroup_id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('placegroup');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'prior', 'url', 'value', 'description');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('prior', $this->prior);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('description', $this->description, true);

        return $this->getByCriteria($criteria);
    }
/*
    public function getAll($userProfile)
    {
        $criteria = new CDbCriteria;
        return $this->getByCriteria($criteria, $userProfile->placegroup_pagesize);
    }
/**/
}