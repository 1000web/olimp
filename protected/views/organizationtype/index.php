<?php
/* @var $this OrganizationTypeController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'organizationtype';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, OrganizationType::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
