<?php
/* @var $this Controller */
/* @var $customer Customer */
/* @var $organization Organization */
/* @var $task Task */
/* @var $deal Deal */

$content = array();
//----------------------------------------------------------------------------------------------------------------------
$title = 'Организации';
$data = $organization;
$controller = 'organization';
if (MyHelper::checkAccess($controller, 'view')) {
    $tab = '<h2>'.$title . '</h2>';
    $tab .= $this->renderPartial('../' . $controller . '/index', array(
        'dataProvider' => $data,
        'no_filter_buttons' => true,
    ), true);
    $content[] = array(
        'label' => $title . ' ('.count($data->getData()).')',
        'content' => $tab,
        'active' => false,
    );
}
//----------------------------------------------------------------------------------------------------------------------
$title = 'Задачи';
$data = $task;
$controller = 'task';
if (MyHelper::checkAccess($controller, 'view')) {
    $tab = '<h2>'.$title . '</h2>';
    $tab .= $this->renderPartial('../' . $controller . '/index', array(
        'dataProvider' => $data,
        'no_filter_buttons' => true,
    ), true);
    $content[] = array(
        'label' => $title . ' ('.count($data->getData()).')',
        'content' => $tab,
        'active' => false,
    );
}
//----------------------------------------------------------------------------------------------------------------------
$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'placement' => 'above', // above, below, right, left
    'tabs' => $content,
));