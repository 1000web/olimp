<?php
/* @var $this OrganizationContactController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'organizationcontact';
$this->columnLabels($controller);
if($this->getAction()->getId() != 'search' AND !isset($no_filter_buttons)) $this->renderPartial('../' . $controller . '/_filter_buttons');
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, OrganizationContact::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
