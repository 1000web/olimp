<?php
/* @var $this PlaceController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'place';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, Place::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
