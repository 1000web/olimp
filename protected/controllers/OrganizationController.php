<?php

class OrganizationController extends Controller
{
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $userProfile = $this->getUserProfile();
        $this->loadModel($id);
        $this->buildPageOptions();

        $userProfile->account_pagesize = 100;
        $userProfile->deal_pagesize = 100;
        $userProfile->organizationcontact_pagesize = 100;
        $userProfile->customer_pagesize = 100;

        // если это не 1-я организация, показываем кнопки Купить у них и Продать им.
        if($this->_model->id != 1) $this->description =
            $this->widget('bootstrap.widgets.TbButton', array(
                'url' => array('/deal/create', 'zakaz_oid' => 1, 'post_oid' => $this->_model->id),
                'label' => 'Купить у них',
                'type' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            ), true)
            . ' ' .
            $this->widget('bootstrap.widgets.TbButton', array(
                'url' => array('/deal/create', 'zakaz_oid' => $this->_model->id, 'post_oid' => 1),
                'label' => 'Продать им',
                'type' => 'warning', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            ), true);

        $this->render('view', array(
            'account' => Account::model()->getAll($userProfile, 'organization_id', $id),
            'contact' => OrganizationContact::model()->getAll($userProfile, 'organization_id', $id),
            'customer' => Customer::model()->getAll($userProfile, 'organization_id', $id),

            'deal_zakaz' => Deal::model()->getAll($userProfile, 'organization_zakaz_id', $id),
            'deal_pay' => Deal::model()->getAll($userProfile, 'organization_pay_id', $id),
            'deal_post' => Deal::model()->getAll($userProfile, 'organization_post_id', $id),

            'specification_gruz' => Specification::model()->getAll($userProfile, 'organization_gruz_id', $id),
            'specification_end' => Specification::model()->getAll($userProfile, 'organization_end_id', $id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($copy = NULL)
    {
        if($copy === NULL) {
            $this->_model = new Organization;
        } else {
            $this->loadModel($copy);
            $this->_model->unsetAttributes(array('id'));
            $this->_model->setIsNewRecord(true);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Organization'])) {
            $this->_model->attributes = $_POST['Organization'];
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

        if (isset($_POST['Organization'])) {
            $this->_model->attributes = $_POST['Organization'];
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
        $this->_pagesize = $userProfile->organization_pagesize;
        $this->buildPageOptions();
        $this->render('index', array(
            'dataProvider' => Organization::model()->getAll($userProfile),
        ));
    }

    public function actionSearch()
    {
        $userProfile = $this->getUserProfile();
        $this->show_pagesize = true;
        $this->_pagesize = $userProfile->organization_pagesize;
        $this->buildPageOptions();

        $this->_filter = new Organization('search');
        $this->_filter->unsetAttributes(); // clear any default values
        if (isset($_GET['Organization'])) $this->_filter->attributes = $_GET['Organization'];

        $this->render('index', array(
            'dataProvider' => Organization::model()->getAll($userProfile),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Organization the loaded model
     * @throws CHttpException
     */
    public function loadModel($id = NULL)
    {
        if (isset($_GET['id']) AND $id === NULL) $id = $_GET['id'];
        if ($this->_model === NULL) {
            $this->_model = Organization::model()->findbyPk($id);
            if ($this->_model === NULL) $this->HttpException(404);
        }
        return $this->_model;
    }

    /**
     * Performs the AJAX validation.
     * @param Organization $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'organization-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public $favorite_available = true;

    public function checkFavorite($id)
    {
        if (OrganizationFav::model()->countByAttributes(array(
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
                    $model = new OrganizationFav;
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
                OrganizationFav::model()->findByAttributes(array(
                    'id' => $del,
                    'user_id' => Yii::app()->user->id,
                ))->delete();
            }
            if ($url = Yii::app()->request->getUrlReferrer()) $this->redirect($url);
            else $this->redirect($this->id);
        }
        $this->buildPageOptions();
        $this->show_pagesize = true;
        $this->render('index', array(
            'dataProvider' => Organization::model()->getAll($this->getUserProfile(), 'favorite'),
        ));
    }

}
