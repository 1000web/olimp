<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController
{
    public $layout = '//layouts/admin';

    public $attributes = array();
    public $breadcrumbs = array();
    public $buttons = array();
    public $columns = array();
    public $labels = array();
    public $menu = array();

    public $header_image = '';
    public $h1 = 'Header H1';
    public $description = '';

    public $favorite_available = false;

    protected $_model = NULL;
    protected $_filter = NULL;
    protected $_pagesize = 20;
    protected $show_pagesize = false;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights',
            //'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /*
        public function accessRules()
        {
            if($this->getModule()) $module = $this->getModule()->id . '.';
            else $module = '';
            $controller = $this->getId() . '.';
            $rules = array();
            // разрешения для каждого действия
            foreach ($this->actions as $action) {
                $rules[] = array(
                    'actions' => array($action),
                    'roles' => array($module . $controller . $action),
                );
            }
            // если ни одно правило не сработало, то действие запрещено
            $rules[] = array(
                array('deny'),
            );
            return $rules;
        }/**/

    public function getUserProfile($param = NULL)
    {
        if (Yii::app()->user->id) $model = Yii::app()->user->user();
        if ($model === NULL) return NULL;
        else if ($param === NULL) return $model->profile;
        else return $model->profile->getAttribute($param);
    }

    public function getColumns($param, $avail_cols)
    {
        $param .= '_columns';
        if (Yii::app()->user->id) $user = Yii::app()->user->user();
        // незалогинен пользователь, показываем все
        if ($user === NULL) return $avail_cols;
        // доставем значение этого параметра
        $val = $user->profile->getAttribute($param);
        // нет такого параметра
        if ($val === NULL) $cols = $avail_cols;
        else {
            // что-то есть, показываем те столбцы, что пользователь себе выбрал
            if ($val) $cols = explode(',', $val);
            else {
                // если ничего нет, то сохраняем показываем все столбцы и сохраняем параметр
                $this->setparam($param, implode(',', $avail_cols));
                $cols = $avail_cols;
            }
        }
        return $cols;
    }

    public function buildFilterButton($options, $param)
    {
        $items = array();
        $userProfile = $this->getUserProfile();
        $top_button_title = $this->getLabel($param);
        $top_button_icon = '';
        $filterparam = 'filter_' . $param;
        if ($userProfile->getAttribute($filterparam)) {
            $items[] = array(
                'label' => 'Сбросить фильтр',
                'url' => array('setparam', 'param' => $filterparam, 'value' => 0),
            );
            $items[] = '---';
        }
        foreach ($options as $key => $value) {
            $button = array(
                'label' => $value,
                'url' => array('setparam', 'param' => $filterparam, 'value' => $key),
            );
            if ($userProfile->getAttribute($filterparam) == $key) {
                $button['icon'] = 'ok';
                $top_button_icon = 'ok';
                $top_button_title = $value;
            }
            $items[] = $button;
        }
        /*
        if (MyHelper::checkAccess($param, 'index')) {
            $items[] = array(
                'label' => 'Список',
                'icon' => 'list',
                'url' => '/' . $param . '/index',
            );
        }/**/
        $this->widget('bootstrap.widgets.TbButtonGroup', array(
            'type' => '', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'buttons' => array(
                array('label' => $top_button_title, 'icon' => $top_button_icon, 'url' => ''),
                array('items' => $items),
            ),
        ));
    }

    public function setparam($param, $value)
    {
        $userProfile = $this->getUserProfile();
        if ($userProfile->getAttribute($param) !== NULL) {
            $userProfile->setAttribute($param, $value);
            $userProfile->save();
        }
    }

    public function actionSetparam($param, $value)
    {
        $this->setparam($param, $value);
        if ($url = Yii::app()->request->getUrlReferrer()) $this->redirect($url);
        else $this->redirect(Yii::app()->homeUrl);
    }

    public function addAttribute($name)
    {
        $item = array('name' => $name, 'label' => $this->_model->getLabel($name));
        $value = $this->_model->getAttributeValue($name);
        if ($value !== NULL) {
            $item['value'] = $value;
            $item['type'] = 'raw';
        }
        $this->attributes[$name] = $item;
    }

    public function addAttributes($list)
    {
        foreach ($list as $item) {
            $this->addAttribute($item);
        }
    }

    public function addButton($controller, $action)
    {
        if (!$controller) $controller = $this->id;
        if ($action == 'copy') $check = 'create';
        else $check = $action;
        if (MyHelper::checkAccess($controller, $check)) {
            $this->buttons[$action] = array(
                'url' => 'Yii::app()->createUrl("' . $controller . '/' . $action . '", array("id"=>$data->id))',
            );
            switch ($action) {
                case 'create':
                    $this->buttons[$action]['url'] = 'Yii::app()->createUrl("' . $controller . '/' . $action . '", array("parent_id"=>$data->id))';
                    $this->buttons[$action]['icon'] = 'icon-plus';
                    break;
                case 'copy':
                    $this->buttons[$action]['url'] = 'Yii::app()->createUrl("' . $controller . '/create", array("copy"=>$data->id))';
                    $this->buttons[$action]['icon'] = 'icon-plus';
                    break;
            }
        }
    }

    public function addButtons($controller, $actions, $preserve = FALSE)
    {
        if ($preserve === FALSE) $this->buttons = array();
        foreach ($actions as $action) {
            $this->addButton($controller, $action);
        }
    }

    public function addColumn($name)
    {
        $item = array('name' => $name, 'header' => $this->getLabel($name));
        $value = $this->getColumnValue($name);
        if ($value !== NULL) {
            $item['value'] = $value;
            $item['type'] = 'raw';
        }
        $this->columns[$name] = $item;
    }

    public function addColumns($list, $preserve = FALSE)
    {
        if ($preserve === FALSE) $this->columns = array();
        foreach ($list as $item) {
            // for example: controller = organization, item = organization_id
            if ($item != $this->id . '_id') $this->addColumn($item);
        }
    }

    public function columnLabels($controller = NULL)
    {
        if ($controller === NULL) $controller = $this->getId();
        $this->labels = MyHelper::labels($controller);
    }

    public function getLabel($item)
    {
        // если массив с метками пустой. то загружаем массив для текущего контроллера
        if (!$this->labels) $this->columnLabels();
        // если такая метка есть, то возвращаем ее
        if (isset($this->labels[$item])) return $this->labels[$item];
        return 'НЕИЗВЕСТНО';
    }

    public function getColumnValue($name)
    {
        return MyHelper::getValue($name, '$data');
    }

    public function buildMenuOperations($id = NULL)
    {
        $items = array(
            'create' => array(
                'label' => Yii::t('lang', 'Создать'),
                'icon' => MyHelper::action_icon('create'),
                'url' => array('create')),
            'index' => array(
                'label' => Yii::t('lang', 'Список'),
                'icon' => MyHelper::action_icon('index'),
                'url' => array('index')),
            'update' => array(
                'label' => Yii::t('lang', 'Редактировать'),
                'icon' => MyHelper::action_icon('update'),
                'url' => array('update', 'id' => $id)),
            'view' => array(
                'label' => Yii::t('lang', 'Показать'),
                'icon' => MyHelper::action_icon('view'),
                'url' => array('view', 'id' => $id)),
            'column' => array(
                'label' => Yii::t('lang', 'Столбцы'),
                'icon' => MyHelper::action_icon('column'),
                'url' => array('column')),
            'favorite' => array(
                'label' => 'Избранное',
                'icon' => MyHelper::action_icon('favorite'),
                'url' => array('favorite'),
            ),
            'favorite_add' => array(
                'label' => 'В Избранное',
                'icon' => MyHelper::action_icon('favorite_add'),
                'url' => array('favorite', 'add' => $id),
            ),
            'favorite_del' => array(
                'label' => 'Уже в Избранном',
                'icon' => MyHelper::action_icon('favorite_del'),
                'url' => array('favorite', 'del' => $id),
            ),
            'delete' => array(
                'label' => Yii::t('lang', 'Удалить'),
                'icon' => MyHelper::action_icon('delete'),
                'url' => '#',
                'linkOptions' => array(
                    'submit' => array('delete', 'id' => $id),
                    'confirm' => Yii::t('lang', 'Вы действительно хотите удалить эту запись?')
                )
            ),
        );
        $this->menu = array();
        switch ($this->getAction()->getId()) {
            case 'create':
                if (MyHelper::checkAccess($this->id, 'index')) $this->menu[] = $items['index'];
                break;
            case 'index':
                if (MyHelper::checkAccess($this->id, 'create')) $this->menu[] = $items['create'];
                if ($this->favorite_available) {
                    if (MyHelper::checkAccess($this->id, 'favorite')) $this->menu[] = $items['favorite'];
                }
                break;
            case 'column':
                if (MyHelper::checkAccess($this->id, 'index')) $this->menu[] = $items['index'];
                break;
            case 'update':
                if (MyHelper::checkAccess($this->id, 'index')) $this->menu[] = $items['index'];
                if (MyHelper::checkAccess($this->id, 'view')) $this->menu[] = $items['view'];
                if (MyHelper::checkAccess($this->id, 'delete')) $this->menu[] = $items['delete'];
                break;
            case 'view':
                if ($this->favorite_available) {
                    if ($this->checkFavorite($id)) $this->menu[] = $items['favorite_del'];
                    else $this->menu[] = $items['favorite_add'];
                }
                if (MyHelper::checkAccess($this->id, 'index')) $this->menu[] = $items['index'];
                if (MyHelper::checkAccess($this->id, 'update')) $this->menu[] = $items['update'];
                if (MyHelper::checkAccess($this->id, 'delete')) $this->menu[] = $items['delete'];
                break;
            case 'favorite':
                if (MyHelper::checkAccess($this->id, 'index')) $this->menu[] = $items['index'];
                break;
        }
        return;
    }

    public function buildBreadcrumbs($id)
    {

        $item = Item::model()->findByPk($id);
        if (!$item) {
            // Нет такого пункта, показываем в виде ошибки чтобы исправить
            $this->breadcrumbs = array('Unknown Controller');
            return;
        }
        // Home page site/index
        if (!$item->parent_id) return;
        //if($item->parent_id == 1) return;
        if (!$this->breadcrumbs) {
            // это первая крошка
            $this->breadcrumbs = array($item->value);
        } else {
            // не первая крошка
            $val = $item['value'];
            $url = MyHelper::createURL($item->module, $item->controller, $item->action);
            if (isset($this->_model->id) AND $item->action == 'view') {
                if (!empty($this->_model->value)) $val = $this->_model->value;
                $url['id'] = $this->_model->id;
            }
            $this->breadcrumbs = CMap::mergeArray(
                array($val => $url),
                $this->breadcrumbs
            );
        }
        $this->buildBreadcrumbs($item->parent_id);
    }

    public function save_current_page()
    {
        if (Yii::app()->user->id) {
            $rights = '';
            $rights_flag = false;
            if (MyHelper::checkAccess($this->id, 'view')) {
                if ($rights_flag) $rights .= ', ';
                $rights .= 'Чтение';
                $rights_flag = true;
            }
            if (MyHelper::checkAccess($this->id, 'update')) {
                if ($rights_flag) $rights .= ', ';
                $rights .= 'Редактирование';
                $rights_flag = true;
            }
            if (MyHelper::checkAccess($this->id, 'delete')) {
                if ($rights_flag) $rights .= ', ';
                $rights .= 'Удаление';
                $rights_flag = true;
            }
            $this->setparam('current_page_url', Yii::app()->getRequest()->getRequestUri());
            $this->setparam('current_page_datetime', time());
            $this->setparam('current_page_rights', $rights);
        }
    }

    public function check_current_page()
    {
        $text = '';
        $n = 0;
        $criteria = new CDbCriteria;
        // за последние сутки
        $criteria->addCondition('profiles.current_page_datetime > :time');
        $criteria->params[':time'] = time() - 86400;
        // кроме самого этого юзера
        $criteria->addCondition('id != :id');
        $criteria->params[':id'] = Yii::app()->user->id;
        // с такой же текущей страницей
        $criteria->addCondition('profiles.current_page_url = :url');
        $criteria->params[':url'] = Yii::app()->getRequest()->getRequestUri();

        $criteria->with = array('profiles');
        $criteria->order = 'profiles.last_name, profiles.first_name';
        $users = Users::model()->findAll($criteria);
        foreach ($users as $user) {
            if ($user->profiles->current_page_datetime) $text .= MyHelper::datetime_format($user->profiles->current_page_datetime) . ' ';
            $text .= $user->profiles->last_name . ' ' . $user->profiles->first_name . ' (' . $user->username . ') ';
            if (!empty($user->profiles->current_page_rights)) $text .= 'с правами ' . $user->profiles->current_page_rights . "<br />\n";
            $n++;
        }
        if ($n != 0) {
            if ($n == 1) $header = "Эту страницу также просматривает";
            else $header = "Эту страницу также просматривают";
            $header = "<strong>" . $header . "</strong>:<br />\n";
            Yii::app()->user->setFlash('warning', $header . $text);
        }
    }

    public function buildPageOptions()
    {
        if($this->getModule()) $module = $this->getModule()->getId();
        else $module = NULL;

        $item = Item::model()->findByAttributes(array(
            'module' => $module,
            'controller' => $this->id,
            'action' => $this->getAction()->getId(),
        ));

        $this->save_current_page();
        if (Yii::app()->getRequest()->getRequestUri() != '/' AND Yii::app()->user->id) {
            $this->check_current_page();
        }

        $this->buildBreadcrumbs($item->id);

        $this->header_image = $this->insertImage('150x150');

        if ($this->_model) {
            $this->h1 = (isset($this->_model->value) ? $this->_model->value : $item['h1']);
            $this->description = $this->_model->description;
            $this->pageTitle = (isset($this->_model->value) ? $this->_model->value : $item['h1']) . ' - ' . $item['title'];

            $this->buildMenuOperations($this->_model->id);
        } else {
            $this->h1 = $item['h1'];
            $this->description = $item['description'];
            $this->pageTitle = $item['title'];

            $this->buildMenuOperations();
        }
    }

    public function insertImage($size)
    {
        $images_folder = 'images';
        $basePath = Yii::app()->basePath . '/..';
        $img = '/' . $images_folder . '/' . $size . '/' . $this->id . '/' . $this->getAction()->getId() . '.jpg';
        if (!file_exists($basePath . $img)) {
            $img = '/' . $images_folder . '/' . $size . '/' . $this->id . '/index.jpg';
            if (!file_exists($basePath . $img)) {
                $img = '/' . $images_folder . '/' . $size . '/nophoto.jpg';
            }
        }
        return $img;
    }

    public function submit3buttons()
    {
        echo "\n\n<div class='row buttons text-center'>\n";
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Сохранить',
            'type' => 'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'buttonType' => 'submit',
            'htmlOptions' => array('class' => 'span2 offset1'),
            'size' => 'large',
        ));
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Сохранить и создать еще',
            'type' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'htmlOptions' => array('class' => 'span3 offset1', 'name' => 'create_new', 'id' => 'create_new', 'value' => 1),
            'buttonType' => 'submit',
            'size' => 'large',
        ));
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Отменить',
            'url' => Yii::app()->request->getUrlReferrer(),
            'type' => 'danger', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'htmlOptions' => array('class' => 'span2 offset1'),
            //'buttonType' => 'submit',
            'size' => 'large', // null, 'large', 'small' or 'mini'
        ));
        echo "</div>\n\n";
    }

    public function submit_button()
    {
        $ret = "\n\n<div class='row buttons text-center'>\n";
        $ret .= $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Отправить',
            'type' => 'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'buttonType' => 'submit',
            'htmlOptions' => array('class' => 'span3'),
        ), true);
        $ret .= "</div>\n\n";
        return $ret;
    }

    public function HttpException($code)
    {
        switch ($code) {
            case 404:
                $error = 'Запрашиваемая страница не существует.';
                break;
            default:
                $error = 'Ошибка ' . $code;
        }
        throw new CHttpException($code, $error);
    }

    public function pagesize($controller = NULL)
    {
        if ($controller == NULL) $controller = $this->id;
        $available_pagesize = array(5, 10, 20, 50, 100);
        $buttons = array(
            array('label' => 'На странице', 'disabled' => true)
        );
        foreach ($available_pagesize as $pagesize) {
            $button = array(
                'label' => $pagesize,
                'url' => array('setparam', 'param' => $controller . '_pagesize', 'value' => $pagesize)
            );
            if ($pagesize == $this->_pagesize) $button['active'] = true;
            $buttons[] = $button;
        }
        $this->widget('bootstrap.widgets.TbButtonGroup', array(
            'buttons' => $buttons
        ));
    }

    public function actionView($id)
    {
        $this->_model = $this->loadModel($id);
        $this->buildPageOptions();
        $this->render('../detail_view');
    }

}