<?php

/**
 * This is the model class for table "{{image}}".
 *
 * The followings are the available columns in table '{{image}}':
 * @property integer $id
 * @property integer $imagegroup_id
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Users $update_user
 */
class Image extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Image the static model class
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
        return '{{image}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('value', 'required'),
            array('value', 'length', 'max' => 255),
            array('imagegroup_id', 'numerical', 'integerOnly' => true),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, imagegroup_id, value, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'imagegroup' => array(self::BELONGS_TO, 'Imagegroup', 'imagegroup_id'),
            //'organization_contacts' => array(self::HAS_MANY, 'OrganizationContact', 'image_id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('image');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'imagegroup_id', 'prior', 'visible', 'value', 'description');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('imagegroup', $this->imagegroup_id, true);
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

    public function getVisible($section, $url, $pagesize = -1)
    {
        $criteria = new CDbCriteria;
        $criteria->order = 't.prior ASC';
        $criteria->addCondition('t.visible=1');
        $criteria->addCondition('t.section=:section');
        $criteria->addCondition('t.url=:url');
        $criteria->params = array(
            ':section' => $section,
            ':url' => $url,
        );
        return $this->getByCriteria($criteria, $pagesize);
    }

    public function getCron($num = 5)
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('t.cron=0');
        return $this->getByCriteria($criteria, $num);
    }

}