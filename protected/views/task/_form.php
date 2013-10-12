<?php
/* @var $this TaskController */
/* @var $this ->_model Task */
/* @var $form CActiveForm */

//Yii::app()->bootstrap->registerAssetJs('locales/bootstrap-datepicker.ru.js');

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'verticalForm',
    //'method' => 'get',
    'htmlOptions' => array(
        //'class'=>'form-inline',
        'class' => 'well'
    ),
));

echo $form->errorSummary($this->_model);
echo '<div class="input-inline">';
echo $form->datepickerRow($this->_model, 'date', array(
    'class' => 'text-center',
    'prepend' => '<i class="icon-calendar"></i>',
    'options' => array(
        'format' => 'dd-mm-yyyy',
        'viewMode' => 1,
        'autoclose' => true,
        'language' => 'ru',
        'weekStart' => 1,
    )
));
echo $form->timepickerRow($this->_model, 'time', array(
    'class' => 'text-center',
    'prepend' => '<i class="icon-time"></i>',
    'options' => array(
        'showMeridian' => false,
        'minuteStep' => 1,
        'showSeconds' => true,
        'showInputs' => true,
        'template' => 'dropdown', //dropdown, modal
    ),
));
//$this->widget('bootstrap.widgets.TbButton', array('icon'=>'icon-time', 'disabled' => true));
echo '</div>';
echo $form->dropDownListRow($this->_model, 'user_id', Users::model()->getOptions('id', 'username'), array('class' => 'input-block-level'));
/*
$datetime = date('H:i:s', $this->_model->datetime);
echo $form->timepickerRow($this->_model, 'datetime', array('class' => 'inline'));
/**/

echo $form->dropDownListRow($this->_model, 'task_type_id', TaskType::model()->getOptions(), array('class' => 'input-block-level'));
/*
// Нельзя менять этапы через форму
if (!$this->_model->isNewRecord) echo $form->dropDownListRow($this->_model, 'task_stage_id', TaskStage::model()->getOptions('id', 'value', 'prior'), array('class' => 'input-block-level'));
/**/
echo $form->dropDownListRow($this->_model, 'task_prior_id', TaskPrior::model()->getOptions('id', 'value', 'prior'), array('class' => 'input-block-level'));

echo $form->textField($this->_model, 'value', array(
    'maxlength' => 255,
    'class' => 'input-block-level',
    'placeholder' => $this->_model->getLabel('value'),
));
echo $form->textArea($this->_model, 'description', array('rows' => 4,
    'class' => 'input-block-level',
    'placeholder' => $this->_model->getLabel('description'),
));

$this->submit3buttons();

$this->endWidget();