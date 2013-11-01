<?php

class SiteController extends FrontendController
{
    public function actionIndex()
    {
        $this->buildPageOptions();

        $posts[] =  array(
            'id' => '24',
            'date' => '21-10-2013',
            'url' => 'mutko-schitaet-chto-samoe-glavnoe-ne-promaxnutsya-s-vyxodom-na-pik-formy-pered-igrami-v-sochi',
            'title' => 'Мутко считает, что самое главное – не промахнуться с выходом на пик формы перед Играми в Сочи',
            'categories' => array(
                array('id' => 1, 'url' => 'news', 'value' => 'Новости'),
                array('id' => 2, 'url' => 'medals', 'value' => 'Медали'),
                array('id' => 3, 'url' => 'reports', 'value' => 'Репортажи'),
            ),
        );
        $this->render('index', array(
            'posts' => $posts,
        ));
    }

    public function actionLogin()
    {
        if (Yii::app()->user->isGuest) $this->redirect('/user/login');
        else $this->redirect('/');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else {
                $this->buildPageOptions();
                $this->render('error', $error);
            }
        }
    }

    public function actionPlace($url = NULL)
    {
        $this->buildPageOptions();

        if($url !== NULL) {
            $this->buildMetaCanonical('/place/' . $url);
            $place_arr = Place::model()->getByUrl($url)->getData();
            $place = $place_arr[0];
            $this->render('place', array(
                'place' => $place,
            ));
        } else {
            $this->buildMetaCanonical('/place');
            $this->render('places', array(
                'places' => Place::model()->getVisible()->getData(),
            ));
        }
    }

    public function actionSport($url = NULL)
    {
        $this->buildPageOptions();

        if($url !== NULL) {
            $this->buildMetaCanonical('/sport/' . $url);
            $sport_arr = Sport::model()->getByUrl($url)->getData();
            $sport = $sport_arr[0];
            $this->render('sport', array(
                'sport' => $sport,
                'text' => Sporttext::model()->getById($sport->id)->getData(),
            ));
        } else {
            $this->buildMetaCanonical('/sport');
            $this->render('sports', array(
                'sports' => Sport::model()->getVisible(100)->getData(),
            ));
        }
    }

    public function actionTag($url = NULL)
    {
        $this->buildPageOptions();
/*
        $this->render('tag', array(
            'tags' => Tag::model()->getAll(-1)->getData(),
            //'posts' => Post::model()->getAll(-1)->getData(),
        ));
/**/
        if($url !== NULL) {
            $this->buildMetaCanonical('/tag/' . $url);
            $arr = Tag::model()->getByUrl($url)->getData();
            $tag = $arr[0];
            $this->render('tag', array(
                'tag' => $tag,
            ));
        } else {
            $this->buildMetaCanonical('/tag');
            $this->render('tags', array(
                'tags' => Tag::model()->getAll()->getData(),
            ));
        }
    }

    public function actionPost($url = NULL)
    {
        $this->buildPageOptions();

        $this->render('post', array(
            'tags' => Tag::model()->getAll(-1)->getData(),
            //'posts' => Post::model()->getAll(-1)->getData(),
        ));
    }

    public function actionCategory($url = NULL)
    {
        $this->buildPageOptions();

        if($url !== NULL) {
            $this->buildMetaCanonical('/category/' . $url);
            $arr = Category::model()->getByUrl($url)->getData();
            $category = $arr[0];
            $this->render('category', array(
                'category' => $category,
                //'text' => Sporttext::model()->getById($sport->id)->getData(),
            ));
        } else {
            $this->buildMetaCanonical('/category');
            $this->render('categories', array(
                'categories' => Category::model()->getAll()->getData(),
            ));
        }
    }

    public function actionDate($y = NULL, $m = NULL, $d = NULL)
    {
        $this->buildPageOptions();
        $posts[] =  array(
            'id' => '24',
            'date' => '21-10-2013',
            'url' => 'mutko-schitaet-chto-samoe-glavnoe-ne-promaxnutsya-s-vyxodom-na-pik-formy-pered-igrami-v-sochi',
            'title' => 'Мутко считает, что самое главное – не промахнуться с выходом на пик формы перед Играми в Сочи',
            'categories' => array(
                array('id' => 1, 'url' => 'news', 'value' => 'Новости'),
                array('id' => 2, 'url' => 'medals', 'value' => 'Медали'),
                array('id' => 3, 'url' => 'reports', 'value' => 'Репортажи'),
            ),
        );
        $this->render('index', array(
            'posts' => $posts,
        ));
    }

}