<?php
/* @var $this MenuItemController */
/* @var $this ->_model MenuItem */
/* @var $form CActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'verticalForm',
    'htmlOptions' => array('class' => 'well'),
));

echo $form->errorSummary($this->_model);

echo $form->dropDownListRow($this->_model, 'parent_id', MenuItem::model()->getOptions('id', array('key' => 'i', 'val' => 'title'), NULL, NULL, TRUE), array('class' => 'input-block-level'));

echo $form->dropDownListRow($this->_model, 'menu_id', Menu::model()->getOptions(), array('class' => 'input-block-level'));

echo $form->dropDownListRow($this->_model, 'item_id', Item::model()->getOptions('id', 'title'), array('class' => 'input-block-level'));

echo $form->textFieldRow($this->_model, 'prior', array('class' => 'input-block-level'));

echo $form->dropDownListRow($this->_model, 'visible', array(0 => 'Скрыто', 1 => 'Видимо'), array('class' => 'input-block-level'));

echo $form->dropDownListRow($this->_model, 'guest_only', array(0 => 'Нет', 1 => 'Да'), array('class' => 'input-block-level'));

$this->submit3buttons();

$this->endWidget();