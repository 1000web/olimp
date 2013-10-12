<?php

/**
 * This is the model class for table "{{item}}".
 *
 * The followings are the available columns in table '{{item}}':
 * @property integer $id
 * @property integer $parent_id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $url
 * @property string $icon
 * @property string $title
 * @property string $h1
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Item $parent
 * @property Item[] $items
 * @property Users $create_user
 * @property Users $update_user
 * @property MenuItem[] $menuItems
 */
class Item extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Item the static model class
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
        return '{{item}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('value', 'required'),
            array('parent_id', 'numerical', 'integerOnly' => true),
            array('module, controller, action, icon', 'length', 'max' => 64),
            array('url, title, h1, value', 'length', 'max' => 255),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, parent_id, module, controller, action, url, icon, title, h1, value, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'parent' => array(self::BELONGS_TO, 'Item', 'parent_id'),
            'childs' => array(self::HAS_MANY, 'Item', 'parent_id'),
            'menuItems' => array(self::HAS_MANY, 'MenuItem', 'item_id'),
            'menus' => array(self::HAS_MANY, 'Menu', 'menu_id', 'through' => 'menuItems'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('item');
    }

    public function getAvailableAttributes()
    {
        return array('id', 'parent_id', 'module', 'controller', 'action', 'icon', 'url', 'title', 'h1', 'value', 'description');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('module', $this->module, true);
        $criteria->compare('controller', $this->controller, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('icon', $this->icon, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('h1', $this->h1, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('description', $this->description, true);

        return $this->getByCriteria($criteria);
    }

    public function getAll($userProfile)
    {
        $criteria = new CDbCriteria;
        if ($userProfile->filter_item_controller) {
            $criteria->addCondition('controller=:controller');
            $criteria->params[':controller'] = $userProfile->filter_item_controller;
        }
        if ($userProfile->filter_item_module) {
            $criteria->addCondition('module=:module');
            $criteria->params[':module'] = $userProfile->filter_item_module;
        }
        if ($userProfile->filter_item_action) {
            $criteria->addCondition('action=:action');
            $criteria->params[':action'] = $userProfile->filter_item_action;
        }
        if ($userProfile->filter_item_parent_id) {
            $criteria->addCondition('parent_id=:parent_id');
            $criteria->params[':parent_id'] = $userProfile->filter_item_parent_id;
        }
        return $this->getByCriteria($criteria, $userProfile->item_pagesize);
    }

}