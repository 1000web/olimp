<?php
$this->breadcrumbs = array(
    UserModule::t("Users"),
);
if (UserModule::isAdmin()) {
    //$this->layout = '//layouts/column2';
    $this->menu = array(
        array('label' => UserModule::t('Manage Users'), 'url' => array('/user/admin')),
        array('label' => UserModule::t('Manage Profile Field'), 'url' => array('profileField/admin')),
    );
}

$this->h1 = UserModule::t("List User");

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'type' => 'striped bordered condensed',
    'columns' => array(
        array(
            'name' => 'username',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
        ),
        'create_at',
        'lastvisit_at',
    ),
));
