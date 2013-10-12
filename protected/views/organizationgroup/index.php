<?php
/* @var $this OrganizationGroupController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'organizationgroup';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, OrganizationGroup::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
