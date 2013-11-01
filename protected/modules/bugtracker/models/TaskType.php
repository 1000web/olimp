<?php

/**
 * This is the model class for table "{{task_type}}".
 *
 * The followings are the available columns in table '{{task_type}}':
 * @property integer $id
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Users $create_user
 * @property Users $update_user
 */
class TaskType extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TaskType the static model class
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
        return '{{task_type}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('value', 'required'),
            array('value', 'length', 'max' => 255),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, value, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('tasktype');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'value', 'description');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('description', $this->description, true);

        return $this->getByCriteria($criteria);
    }

    public function getAll($userProfile)
    {
        $criteria = new CDbCriteria;
        return $this->getByCriteria($criteria, $userProfile->tasktype_pagesize);
    }

}