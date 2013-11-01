<?php
/* @var $this Controller */
/* @var $comment TaskComment */

$this->renderPartial('_actions');

echo $this->renderPartial('../detail_view');

if(empty($this->_model->comments)) {
    echo "<h4>Пока нет комментариев к задаче.</h4>\n\n";
} else {
    echo "<table class='table table-striped table-condensed'>\n";
    echo "<h4>Комментарии:</h4>\n\n";
    foreach($this->_model->comments as $item){
        echo "<tr><td>\n";
        echo "<strong>";
        //echo $item->user->profiles->last_name . ' ' . $item->user->profiles->first_name . ' (' . $item->user->username . ")\n";
        echo $item->user->profiles->last_name . ' ' . $item->user->profiles->first_name . "\n";
        echo "</strong>\n";
        echo MyHelper::datetime_format($item->datetime) . "<br />\n";
        echo $item->comment;
        echo "</td></tr>\n";
    }
    echo "</table>\n\n";
}
// проверяем права на отправку комментариев к задаче
if($this->_model->owner_id == Yii::app()->user->id OR $this->_model->user_id == Yii::app()->user->id)
{
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'verticalForm',
        'htmlOptions' => array('class' => 'well'),
    ));
    echo $form->hiddenField($comment, 'task_id');
    echo $form->hiddenField($comment, 'user_id');
    echo $form->textAreaRow($comment, 'comment', array('rows' => 4, 'class' => 'input-block-level'));

    echo $this->submit_button();

    $this->endWidget();
}

