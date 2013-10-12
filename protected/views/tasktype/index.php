<?php
/* @var $this TaskTypeController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'tasktype';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, TaskType::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
