<?php

/**
 * This is the model class for table "{{tag}}".
 *
 * The followings are the available columns in table '{{tag}}':
 * @property integer $id
 * @property integer $visible
 * @property string $url
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 */
class Tag extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Tag the static model class
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
        return '{{tag}}';
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
            //'customer_contacts' => array(self::HAS_MANY, 'CustomerContact', 'tag_id'),
            //'organization_contacts' => array(self::HAS_MANY, 'OrganizationContact', 'tag_id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('tag');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'visible', 'url', 'value', 'description');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('visible', $this->visible);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('description', $this->description, true);

        return $this->getByCriteria($criteria);
    }

    public function getAll($pagesize = 20)
    {
        $criteria = new CDbCriteria;
        return $this->getByCriteria($criteria, $pagesize);
    }

}