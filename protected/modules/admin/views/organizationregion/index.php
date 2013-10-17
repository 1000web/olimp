<?php
/* @var $this OrganizationRegionController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'organizationregion';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, OrganizationRegion::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
