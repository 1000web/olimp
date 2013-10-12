<?php
/* @var $this SportgroupController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'sportgroup';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, Sportgroup::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
