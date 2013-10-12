<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property integer $superuser
 * @property integer $status
 * @property string $create_at
 * @property string $lastvisit_at
 *
 * The followings are the available model relations:
 * @property Customer[] $customers
 * @property Profiles $profiles
 * @property Task[] $tasks
 */
class Users extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Users the static model class
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
        return '{{users}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('create_at', 'required'),
            array('superuser, status', 'numerical', 'integerOnly' => true),
            array('username', 'length', 'max' => 20),
            array('password, email, activkey', 'length', 'max' => 128),
            array('lastvisit_at', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, username, password, email, activkey, superuser, status, create_at, lastvisit_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'profiles' => array(self::HAS_ONE, 'Profiles', 'user_id'),
            'customers' => array(self::HAS_MANY, 'Customer', 'user_id'),
            'tasks' => array(self::HAS_MANY, 'Task', 'user_id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('users');
    }

    /*
        public function defaultScope(){
            return array(
                'with'=> array('profiles')
            );
        }
    /**/
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('activkey', $this->activkey, true);
        $criteria->compare('superuser', $this->superuser);
        $criteria->compare('status', $this->status);
        $criteria->compare('create_at', $this->create_at, true);
        $criteria->compare('lastvisit_at', $this->lastvisit_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getOptions($id = 'id', $value = 'value', $order = NULL, $param = NULL, $optional = false)
    {
        $criteria = new CDbCriteria;

        if ($order === NULL) {
            if (!is_array($value)) $order = $value;
            else $order = $id;
        }
        $criteria->order = 't.' . $order;
        $criteria->distinct = true;

        if ($param AND in_array($param, $this->getAllowedRange($id))) {
            $criteria->addCondition($id . '=:param');
            $criteria->params[':param'] = $param;
        }
        $items = $this->findAll($criteria);

        $ret = array();
        if($optional) $ret[0] = '- = Пусто = -';
        foreach ($items as $item) {
            if (is_array($value)) $ret[$item[$id]] = $item[$value['key']][$value['val']];
            else {
                if ($value = 'username') {
                    /*if(Yii::app()->user->id == $item->id) $ret[$item[$id]] = 'Я';
                    else */
                    //$ret[$item[$id]] = $item->profiles->last_name . ' ' . $item->profiles->first_name . ' (' . $item->username . ')';
                    $ret[$item[$id]] = $item->profiles->last_name . ' ' . $item->profiles->first_name . ' (' . $item->username . ')';
                } else $ret[$item[$id]] = $item[$value];
            }
        }
        return $ret;
    }

}