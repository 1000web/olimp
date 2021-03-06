<?php

class DefaultController extends Controller
{
    public function actionView($id)
    {
        $this->_model = Task::model()->findByPk($id);
        if ($this->_model === null) $this->HttpException(404);

        // прилетел параметр для смены статуса задачи
        if (isset($_GET['stage'])) {
            if (in_array($stage = intval($_GET['stage']), TaskStage::model()->getAllowedRange())) {
                $this->_model->setAttribute('task_stage_id', $stage);
                // сохраняем
                if ($this->_model->save()) {
                    // записываем событие в лог
                    $this->redirect(array('view', 'id' => $this->_model->id));
                }
            }
        }
        $comment = new TaskComment;
        if (isset($_POST['TaskComment'])) {
            // проверяем права на отправку комментариев к задаче
            if($this->_model->owner_id == Yii::app()->user->id OR $this->_model->user_id == Yii::app()->user->id)
            {
                $comment->attributes = $_POST['TaskComment'];
                $comment->save();
            }
            $this->redirect(array('view', 'id' => $this->_model->id));
        }
        //$userProfile = $this->getUserProfile();
        //$comment->unsetAttributes(); // clear any default values
        $comment->setAttributes(array(
            'task_id' => $this->_model->id,
            'user_id' => Yii::app()->user->id,
        ));
        $this->buildPageOptions();
        $this->render('view', array(
            //'comment' => TaskComment::model()->getAll($userProfile, 'task_id', $id),
            'comment' => $comment,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($copy = NULL)
    {
        if($copy === NULL) {
            $this->_model = new Task;
            $this->_model->setAttributes(array(
                'date' => date('d-m-Y', time()),
                'time' => date('H:i:s', time()),
                'user_id' => Yii::app()->user->id,
            ));
        } else {
            $this->loadModel($copy);
            $this->_model->unsetAttributes(array('id'));
            $this->_model->setIsNewRecord(true);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Task'])) {
            $this->_model->attributes = $_POST['Task'];

            // если задача назначена самому себе, то статус ставится В работе
            if($this->_model->getAttribute('user_id') == Yii::app()->user->id) $stage = TaskStage::$STAGE_ACTIVE;
            // иначе задача новая, Не принята
            else $stage = TaskStage::$STAGE_NEW;

            $this->_model->setAttributes(array(
                'owner_id' => Yii::app()->user->id,
                'task_stage_id' => $stage,
            ));
            if ($this->_model->save()) {
                if (isset($_POST['create_new'])) $this->redirect(array('create'));
                else $this->redirect(array('view', 'id' => $this->_model->id));
            }
        }
        $this->buildPageOptions();
        $this->render('_form');
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Task'])) {
            //print_r($_POST['Task']); exit;
            $this->_model->attributes = $_POST['Task'];
            if ($this->_model->save()) {
                if (isset($_POST['create_new'])) $this->redirect(array('create'));
                else $this->redirect(array('view', 'id' => $this->_model->id));
            }
        }
        $this->buildPageOptions();
        $this->render('_form');
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id);
        $this->_model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $userProfile = $this->getUserProfile();
        $this->show_pagesize = true;
        $this->_pagesize = $userProfile->task_pagesize;
        $this->buildPageOptions();

        $this->render('index', array(
            'dataProvider' => Task::model()->getAll($userProfile),
        ));
    }

    public function actionSearch()
    {
        $userProfile = $this->getUserProfile();
        $this->show_pagesize = true;
        $this->_pagesize = $userProfile->task_pagesize;
        $this->buildPageOptions();

        $this->_filter = new Task('search');
        $this->_filter->unsetAttributes(); // clear any default values
        if (isset($_GET['Task'])) $this->_filter->attributes = $_GET['Task'];

        $this->render('index', array(
            'dataProvider' => Task::model()->getAll($userProfile),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Task the loaded model
     * @throws CHttpException
     */
    public function loadModel($id = NULL)
    {
        if (isset($_GET['id']) AND $id === NULL) $id = $_GET['id'];
        if ($this->_model === NULL) {
            $this->_model = Task::model()->findbyPk($id);
            if ($this->_model === NULL) $this->HttpException(404);
        }
        return $this->_model;
    }

    /**
     * Performs the AJAX validation.
     * @param Task $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'task-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    public $favorite_available = true;

    public function checkFavorite($id)
    {
        if (TaskFav::model()->countByAttributes(array(
            'id' => $id,
            'user_id' => Yii::app()->user->id,
        ))
        ) return true;
        else return false;
    }

    /**
     * Lists favorite models.
     */
    public function actionFavorite($add = NULL, $del = NULL)
    {
        if ($add OR $del) {
            // добавляем в Избранное
            if (isset($add)) {
                if (!$this->checkFavorite($add)) {
                    $model = new TaskFav();
                    $model->setAttributes(array(
                        'id' => $add,
                        'datetime' => time(),
                        'user_id' => Yii::app()->user->id,
                    ));
                    $model->save();
                }
            }
            // удаляем из Избранного
            if ($del) {
                TaskFav::model()->findByAttributes(array(
                    'id' => $del,
                    'user_id' => Yii::app()->user->id,
                ))->delete();
            }
            if ($url = Yii::app()->request->getUrlReferrer()) $this->redirect($url);
            else $this->redirect($this->id);
        }
        $userProfile = $this->getUserProfile();
        $this->show_pagesize = true;
        $this->_pagesize = $userProfile->task_pagesize;
        $this->buildPageOptions();
        $this->render('index', array(
            'dataProvider' => Task::model()->getAll($userProfile, 'favorite'),
        ));

    }


}
