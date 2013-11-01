<?php

class GlossaryController extends Controller
{
    public function buildMenuOperations($model = NULL)
    {
    }

    public function actionIndex()
    {
        $this->buildPageOptions();
        $this->render('index', array(
            'menu' => MenuItem::model()->getItems('top_menu',5),
        ));
    }
}