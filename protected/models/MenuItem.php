<?php

/**
 * This is the model class for table "{{menu_item}}".
 *
 * The followings are the available columns in table '{{menu_item}}':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $menu_id
 * @property integer $item_id
 * @property integer $prior
 * @property integer $visible
 *
 * The followings are the available model relations:
 * @property MenuItem $parent
 * @property MenuItem[] $menuItems
 * @property Menu $menu
 * @property Item $item
 */
class MenuItem extends MyActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MenuItem the static model class
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
        return '{{menu_item}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('menu_id, item_id, prior, visible', 'required'),
            array('parent_id, menu_id, item_id, prior, visible, guest_only', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, parent_id, menu_id, item_id, prior, visible, guest_only', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //'parent' => array(self::BELONGS_TO, 'MenuItem', 'parent_id'),
            //'parent' => array(self::BELONGS_TO, 'Item', 'parent_id'),
            //'childs' => array(self::HAS_MANY, 'MenuItem', 'parent_id'),
            'm' => array(self::BELONGS_TO, 'Menu', 'menu_id'),
            'i' => array(self::BELONGS_TO, 'Item', 'item_id'),
        );
    }

    public function attributeLabels()
    {
        return MyHelper::labels('menuitem');
    }

    public function defaultScope()
    {
        return array(
            'with' => array('i', 'm')
        );
    }

    public function getAvailableAttributes()
    {
        return array('id', 'parent_id', 'menu_id', 'item_id', 'prior', 'visible', 'guest_only', 'value', 'controller', 'action');
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
        $criteria->compare('menu_id', $this->menu_id);
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('prior', $this->prior);
        $criteria->compare('visible', $this->visible);

        return $this->getByCriteria($criteria);
    }

    function getItems($menu_name, $parent_id = NULL, $levels = 2)
    {
        $criteria = new CDbCriteria;

        $criteria->addCondition('m.value=:menu_name');
        $criteria->params[':menu_name'] = $menu_name;
        $criteria->addCondition('t.visible=1');
        $criteria->order = 't.prior';

        if ($parent_id === NULL) $criteria->addCondition('t.parent_id IS NULL');
        else {
            $criteria->addCondition('t.parent_id=:parent_id');
            $criteria->params[':parent_id'] = $parent_id;
        }
        return MenuItem::model()->with('m', 'i')->findAll($criteria);
    }

    function getItemsArray($menu_name, $parent_id = NULL, $levels = 2)
    {
        $menu_items = $this->getItems($menu_name, $parent_id, $levels);
        $items = array();
        $levels--;
        foreach ($menu_items as $item) {
            if (MyHelper::checkAccess($item['i']['module'], $item['i']['controller'], $item['i']['action'])) {
                if (!Yii::app()->user->isGuest AND $item['guest_only']) continue;
                if (empty($item['i']['url'])) $item['i']['url'] = MyHelper::createURL($item['i']['module'], $item['i']['controller'], $item['i']['action']);
                $newItem = array(
                    'url' => $item['i']['url'],
                    'icon' => $item['i']['icon'],
                    'label' => $item['i']['value'],
                );
                if ($levels > 0 AND $subItems = $this->getItemsArray($menu_name, $item['id'], $levels)) $newItem['items'] = $subItems;
                $items[] = $newItem;
            }
        }
        return $items;
    }

    public function getAll($userProfile)
    {
        $criteria = new CDbCriteria;
        if ($userProfile->filter_menu) {
            $criteria->addCondition('menu_id=:menu');
            $criteria->params[':menu'] = $userProfile->filter_menu;
        }
        if ($userProfile->filter_menu_parent_id) {
            $criteria->addCondition('t.parent_id=:parent_id');
            $criteria->params[':parent_id'] = $userProfile->filter_menu_parent_id;
        }
        return $this->getByCriteria($criteria, $userProfile->menuitem_pagesize);
    }

}