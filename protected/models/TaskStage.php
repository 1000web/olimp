<?php

/**
 * This is the model class for table "{{task_stage}}".
 *
 * The followings are the available columns in table '{{task_stage}}':
 * @property integer $id
 * @property integer $prior
 * @property integer $state
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Users $update_user
 * @property Users $create_user
 */
class TaskStage extends MyActiveRecord
{
    static $STAGE_NEW = 1;
    static $STAGE_FROZEN = 2;
    static $STAGE_ACTIVE = 3;
    static $STAGE_DONE = 4;
    static $STAGE_FAILED = 5;
    static $STAGE_CONFIRMED = 6;
    static $STAGE_CANCELLED = 7;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TaskStage the static model class
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
        return '{{task_stage}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('value', 'required'),
            array('prior, state', 'numerical', 'integerOnly' => true),
            array('value', 'length', 'max' => 255),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, prior, state, value, description', 'safe', 'on' => 'search'),
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
        return MyHelper::labels('taskstage');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'prior', 'state', 'value', 'description');
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
        $criteria->compare('state', $this->state);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('description', $this->description, true);

        return $this->getByCriteria($criteria);
    }

    public function getAll($userProfile)
    {
        $criteria = new CDbCriteria;
        return $this->getByCriteria($criteria, $userProfile->taskstage_pagesize);
    }

}