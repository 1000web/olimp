<?php
/* @var $this Controller */
/* @var $dataProvider CActiveDataProvider */
/* @var $filter CActiveDataProvider */

$template = '';
$width = 15;
foreach ($this->buttons as $key => $value) {
    $template .= '{' . $key . '} ';
    $width += 15;
}
if (!empty($template)) array_push($this->columns,
    array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'template' => $template,
        'buttons' => $this->buttons,
        'htmlOptions' => array(
            'style' => 'text-align:center; width: ' . $width . 'px;',
        ),
    ));

$params = array(
    'id' => 'data-grid',
    'type' => 'striped bordered condensed',
    'template' => '{summary}{items}{pager}',
    'dataProvider' => $dataProvider,
    'enablePagination' => true,
    'columns' => $this->columns,
    'pager' => array(
        //'maxButtonCount' => Yii::app()->controller->isMobile?4:10,
        'maxButtonCount' => 10,
        'class' => 'bootstrap.widgets.TbPager',
    ),
);
if(!empty($this->_filter)) {
    //$params['dataProvider'] = $dataProvider->search();
    $params['dataProvider'] = $this->_filter->search();
    $params['filter'] = $this->_filter;
    //$this->renderPartial('../search');
}

$this->widget('bootstrap.widgets.TbGridView', $params);


