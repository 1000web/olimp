<?php
/* @var $this TaskStageController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'taskstage';
$this->columnLabels($controller);
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
$this->addColumns($this->getColumns($controller, TaskStage::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
