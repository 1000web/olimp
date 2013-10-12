<?php

/**
 * This is the model class for table "{{task}}".
 *
 * The followings are the available columns in table '{{task}}':
 * @property integer $id
 * @property integer $task_type_id
 * @property integer $task_stage_id
 * @property integer $task_prior_id
 * @property integer $create_datetime
 * @property integer $datetime
 * @property integer $user_id
 * @property integer $owner_id
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Users $create_user
 * @property Users $update_user
 * @property Users $user
 * @property TaskType $task_type
 * @property Users[] $fav_users
 */
class Task extends MyActiveRecord
{
    public function beforeSave()
    {
        if (parent::beforeSave()) {
            if($this->date AND $this->time) {
                list($hr, $min, $sec) = explode(':', $this->time);
                list($day, $month, $year) = explode('-', $this->date);
                $this->datetime = CTimestamp::getTimestamp(intval($hr), intval($min), intval($sec), intval($month), intval($day), intval($year));
            }
            if($this->isNewRecord) $this->create_datetime = time();
            return true;
        } else return false;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Task the static model class
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
        return '{{task}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('value', 'required'),
            array('task_type_id, task_stage_id, task_prior_id, create_datetime, datetime, user_id, owner_id', 'numerical', 'integerOnly' => true),
            array('date, time', 'length', 'max' => 10),
            array('value', 'length', 'max' => 255),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, task_type_id, task_stage_id, task_prior_id, create_datetime, datetime,
            date, time, user_id, owner_id, value, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'owner' => array(self::BELONGS_TO, 'Users', 'owner_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'task_type' => array(self::BELONGS_TO, 'TaskType', 'task_type_id'),
            'task_stage' => array(self::BELONGS_TO, 'TaskStage', 'task_stage_id'),
            'task_prior' => array(self::BELONGS_TO, 'TaskPrior', 'task_prior_id'),
            'comments' => array(self::HAS_MANY, 'TaskComment', 'task_id'),
            //'fav_users' => array(self::MANY_MANY, 'Users', '{{task_fav}}(id, user_id)'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('task');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'create_datetime', 'datetime', 'task_type_id', 'task_stage_id', 'task_prior_id', 'owner_id', 'user_id', 'value', 'description');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('task_type', 'task_stage', 'task_prior', 'owner', 'user');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('task_type.value', $this->task_type_id, true);
        $criteria->compare('task_stage.value', $this->task_stage_id, true);
        $criteria->compare('task_prior.value', $this->task_prior_id, true);
        $criteria->compare('t.create_datetime', $this->create_datetime);
        $criteria->compare('t.datetime', $this->datetime);
        $criteria->compare('owner.username', $this->owner_id, true);
        $criteria->compare('user.username', $this->user_id, true);
        $criteria->compare('t.value', $this->value, true);
        $criteria->compare('t.description', $this->description, true);

        return $this->getByCriteria($criteria);
    }

    public function getAll($userProfile, $select = '')
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('task_stage');
        switch ($select) {
            case 'favorite':
                $criteria->join = 'LEFT JOIN {{task_fav}} j ON j.id=t.id';
                $criteria->condition = 'j.user_id=:userid';
                $criteria->params[':userid'] = Yii::app()->user->id;
                break;
        }
        if ($userProfile->filter_task_type_id) {
            $criteria->addCondition('task_type_id=:type');
            $criteria->params[':type'] = $userProfile->filter_task_type_id;
        }
        if ($userProfile->filter_task_stage_id) {
            $criteria->addCondition('task_stage_id=:stage');
            $criteria->params[':stage'] = $userProfile->filter_task_stage_id;
        }
        if ($userProfile->filter_task_prior_id) {
            $criteria->addCondition('task_prior_id=:prior');
            $criteria->params[':prior'] = $userProfile->filter_task_prior_id;
        }
        if ($userProfile->filter_task_owner_id) {
            $criteria->addCondition('owner_id=:owner');
            $criteria->params[':owner'] = $userProfile->filter_task_owner_id;
        }
        if ($userProfile->filter_task_user_id) {
            $criteria->addCondition('t.user_id=:user_id');
            $criteria->params[':user_id'] = $userProfile->filter_task_user_id;
        }
        if ($userProfile->filter_task_status) {
            $criteria->addCondition('task_stage.state=:state');
            $criteria->params[':state'] = $userProfile->filter_task_status;
        }
        return $this->getByCriteria($criteria, $userProfile->task_pagesize);
    }

}