<?php
/* @var $this ItemController */
/* @var $this ->_model Item */
/* @var $form CActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'verticalForm',
    'htmlOptions' => array('class' => 'well'),
));

echo $form->errorSummary($this->_model);

echo $form->dropDownListRow($this->_model, 'parent_id', $this->_model->getOptions('id', 'title', 'title', NULL, TRUE));

echo $form->textFieldRow($this->_model, 'module', array('size' => 60, 'maxlength' => 64));

echo $form->textFieldRow($this->_model, 'controller', array('size' => 60, 'maxlength' => 64));

echo $form->textFieldRow($this->_model, 'action', array('size' => 60, 'maxlength' => 64));

echo $form->textFieldRow($this->_model, 'url', array('size' => 60, 'maxlength' => 255));

echo $form->textFieldRow($this->_model, 'icon', array('size' => 60, 'maxlength' => 64));

echo $form->textFieldRow($this->_model, 'title', array('size' => 60, 'maxlength' => 255));

echo $form->textFieldRow($this->_model, 'h1', array('size' => 60, 'maxlength' => 255));

echo $form->textFieldRow($this->_model, 'value', array('size' => 60, 'maxlength' => 255));

echo $form->textAreaRow($this->_model, 'description', array('rows' => 6, 'cols' => 50));

$this->submit3buttons();

$this->endWidget();