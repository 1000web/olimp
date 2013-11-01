<?php

class FrontendController extends RController
{
    public $layout = '//layouts/site';

    public $breadcrumbs = array();

    public $meta_title = '';
    public $meta_canonical = NULL;
    public $meta_prev = NULL;
    public $meta_next = NULL;

    public $meta_keywords = NULL;
    public $meta_description = NULL;
    public $default_keywords = NULL;
    public $default_description = NULL;

    public $menu = array();
    public $widgets = array();

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

    public function addWidget($title, $content)
    {
        $this->widgets[] = array(
            'title' => $title,
            'content' => $content,
        );
    }

    public function buildWidgets()
    {
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

        $cont = "
            <ul>
                <li><a href='/date/2013/10/' title='Октябрь 2013'>Октябрь 2013</a>&nbsp;(36)</li>
                <li><a href='/date/2013/09/' title='Сентябрь 2013'>Сентябрь 2013</a>&nbsp;(51)
                </li>
            </ul>\n";
        $this->addWidget('Архивы', $cont);

        $cont = "<div class='tagcloud'>\n";
        foreach (Tag::model()->getAll()->getData() as $tag) {
            $cont .= CHtml::link($tag->value, '/tag/' . $tag->url, array(
                        'title' => $tag->value,
                        'class' => 'tag-link tag-link-' . $tag->id
                    )
                ) . '&nbsp;';
        }
        $cont .= "</div>\n\n";
        $this->addWidget('Метки', $cont);

        $cont = "<div class='profile'>\n";
        if(Yii::app()->user->isGuest) {
            $cont .= "<a href='/user/login'>Войти</a>";
            $cont .= "<br /><a href='/user/registration'>Регистрация</a>";
        } else {
            if(Yii::app()->user->isAdmin()) {
                $cont .= "<a href='/bugtracker'>Bugtracker</a><br />";
            }
            $cont .= "<a href='/user/profile'>Мой профиль</a>";
            $cont .= "<br /><a href='/user/logout'>Выйти</a>";
        }
        $cont .= "</div>\n\n";
        $this->addWidget('Профиль', $cont);


    }

    public function buildMetaTitle($title = NULL)
    {
        $parts = array();

        if ($title !== null) {
            if (!is_array($title)) $title = array($title);
            $parts = $title;
        } else {
            if (($breadcrumbs = $this->breadcrumbs) !== array()) {
                foreach ($breadcrumbs as $key => $value) $parts[] = is_string($key) || is_array($value) ? $key : $value;

                $parts = array_reverse($parts);
            } else {
                $name = ucfirst($this->getId());
                $action = $this->getAction();
                $module = $this->getModule();
/**/
                if ($action !== null && strcasecmp($action->getId(), $this->defaultAction)) $parts[] = ucfirst($action->getId()) . ' ' . $name;
                else if ($module !== null && strcasecmp($name, $module->defaultController)) $parts[] = $name;
/**/
                if ($module !== null) {
                    $pieces = explode('/', $module->getId());
                    foreach (array_reverse($pieces) as $piece)
                        $parts[] = ucfirst($piece);
                }
            }
        }
        $parts[] = Yii::app()->name;
        $this->meta_title = implode($parts, ' - ');
    }

    public function buildMetaCanonical($url)
    {
        $this->meta_canonical = 'http://' . Yii::app()->request->getServerName() . $url;
    }

    public function buildPageOptions()
    {
        //$this->buildBreadcrumbs($item->id);
        $this->buildWidgets();

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

}