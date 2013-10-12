<?php
/* @var $this MenuItemController */
/* @var $dataProvider CActiveDataProvider */

$controller = 'menuitem';
$this->columnLabels($controller);
if($this->getAction()->getId() != 'search' AND !isset($no_filter_buttons)) $this->renderPartial('../' . $controller . '/_filter_buttons');
$this->addButtons($controller, array('view', 'update', 'copy', 'delete'));
/*
$this->addColumns(array('menu_id', 'id', 'item_id', 'parent_id', 'prior', 'visible', 'guest_only'));
$this->addColumns(array('value', 'controller', 'action'), true);
/**/
/*
$this->addColumn('value', '$data->i->value');
$this->addColumn('controller', '$data->i->controller');
$this->addColumn('action', '$data->i->action');
/**/
$this->addColumns($this->getColumns($controller, Menu::model()->getAvailableAttributes()));

echo $this->renderPartial('../grid_view', array(
    'dataProvider' => $dataProvider,
));
