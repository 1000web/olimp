<?php
/* @var $this PlacegroupController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'placegroup';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, Placegroup::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
