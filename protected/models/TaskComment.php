<?php

/**
 * This is the model class for table "{{task_comment}}".
 *
 * The followings are the available columns in table '{{task_comment}}':
 * @property integer $id
 * @property integer $task_id
 * @property integer $user_id
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Task $task
 */
class TaskComment extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TaskComment the static model class
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
        return '{{task_comment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('task_id, user_id, comment', 'required'),
            array('task_id, user_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, task_id, user_id, comment', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('taskcomment');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'task_id', 'user_id', 'comment');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('task_id', $this->task_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('comment', $this->comment, true);

        return $this->getByCriteria($criteria);
    }

    public function getAll($userProfile)
    {
        $criteria = new CDbCriteria;
        return $this->getByCriteria($criteria, $userProfile->task_pagesize);
    }
}