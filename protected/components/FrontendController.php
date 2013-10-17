<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FrontendController extends RController
{
    public $layout = '//layouts/site';

    public $attributes = array();
    public $breadcrumbs = array();
    public $tags = array();

    public $meta_title = 'Заголовок страницы';
    public $meta_keywords = '';
    public $meta_description = '';

    public $meta_canonical = '';
    public $meta_prev = '';
    public $meta_next = '';

    public $menu = array();
    public $widgets = array();

    public $header_image = '';
    public $h1 = 'Header H1';
    public $description = '';

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

    // атрибуты используются в отношении Моделей,
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

    public function addWidget($title, $content){
        $this->widgets[] = array(
            'title' => $title,
            'content' => $content,
        );
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

    public function buildTags(){
        $this->tags = Tag::model()->getAll()->getData();
    }

    public function buildWidgets(){
        $cont = "
            <ul>
                <li>
                    <a href='/post/mutko-schitaet-chto-samoe-glavnoe-ne-promaxnutsya-s-vyxodom-na-pik-formy-pered-igrami-v-sochi/'
                       title='Мутко считает, что самое главное – не промахнуться с выходом на пик формы перед Играми в Сочи'>Мутко
                        считает, что самое главное – не промахнуться с выходом на пик формы перед Играми в Сочи</a>
                    <span class='post-date'>11.10.2013</span>
                </li>
            </ul>";
        $this->addWidget('Свежие записи', $cont);

        $cont = "
        <ul>
            <li class='cat-item'><a href='/category/news/' title='Просмотреть все записи в рубрике &laquo;Новости&raquo;'>Новости</a> (87)</li>
        </ul>\n";
        $this->addWidget('Рубрики', $cont);

        $cont  = "
            <ul>
                <li><a href='/date/2013/10/' title='Октябрь 2013'>Октябрь 2013</a>&nbsp;(36)</li>
                <li><a href='/date/2013/09/' title='Сентябрь 2013'>Сентябрь 2013</a>&nbsp;(51)
                </li>
            </ul>\n";
        $this->addWidget('Архивы', $cont);

        $cont  = "<div class='tagcloud'>\n";
                if (isset($this->tags)) {
                    foreach ($this->tags as $tag) {
                        $cont .= CHtml::link($tag->value, '/tag/' . $tag->url, array(
                                    'title' => $tag->value,
                                    'class' => 'tag-link tag-link-' . $tag->id
                                )
                            ) . '&nbsp;';
                    }
                }
        $cont .= "</div>\n\n";
        $this->addWidget('Метки', $cont);


    }

    public function buildMetaTags(){
        $this->meta_title = 'Зимние Олимпийские игры 2014 в Сочи | Расписание олимпийских игр, виды спорта, трансляции, страны, рейтинги, результаты, медали';
        $this->meta_keywords = 'мероприятия,пресс-релиз,фото,олимпийский огонь,ледовый дворец спорта,спортивные объекты,фигурное катание';
        $this->meta_description = '';
    }

    public function buildMetaCanonical($url) {
        $this->meta_canonical = 'http://' . Yii::app()->request->getServerName() . $url;
    }

    public function buildPageOptions()
    {
        if($this->getModule()) $module = $this->getModule()->getId();
        else $module = '';

        $item = Item::model()->findByAttributes(array(
            'module' => $module,
            'controller' => $this->id,
            'action' => $this->getAction()->getId(),
        ));

        //$this->buildBreadcrumbs($item->id);
        $this->buildMetaTags();
        $this->buildTags();
        $this->buildWidgets();

        $this->header_image = $this->insertImage('150x150');

        if ($this->_model) {
            $this->h1 = (isset($this->_model->value) ? $this->_model->value : $item['h1']);
            $this->description = $this->_model->description;
            $this->pageTitle = (isset($this->_model->value) ? $this->_model->value : $item['h1']) . ' - ' . $item['title'];
        } else {
            $this->h1 = $item['h1'];
            $this->description = $item['description'];
            $this->pageTitle = $item['title'];
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