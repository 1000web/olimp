<?php
$this->breadcrumbs = array(
    UserModule::t('Profile Fields') => array('admin'),
    UserModule::t('Create'),
);
$this->menu = array(
    array('label' => UserModule::t('Manage Profile Field'), 'url' => array('admin')),
    array('label' => UserModule::t('Manage Users'), 'url' => array('/user/admin')),
);

$this->h1 = UserModule::t('Create Profile Field');

echo $this->renderPartial('_form', array('model' => $model));