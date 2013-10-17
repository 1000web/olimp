<?php
/* @var $this Controller */

// добавляем в Вид все возможные столбцы этой модели для отображения
$this->addAttributes($this->_model->getAvailableAttributes());

$this->widget('bootstrap.widgets.TbDetailView', array(
    'type' => 'striped bordered condensed',
    'attributes' => $this->attributes,
    'data' => $this->_model,
));
