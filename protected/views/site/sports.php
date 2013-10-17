<?php
/* @var $this SportController */
/* @var $sports array */

?>
<h2>Виды спорта на зимних Олимпийских Играх 2014 в Сочи</h2>
<?php

$this->breadcrumbs = array(
    'Виды спорта'
);

foreach ($sports as $sport) {
    ?>
    <div class="media">
        <div class="pull-left">
            <?php
            $img = MyHelper::insertImage(Yii::app()->theme->baseUrl . '/images/olimpic-games/' . $sport->url . '.png', $sport->value, array('class'=>'media-object'));
            echo CHtml::link($img, '/sport/' . $sport->url, array(
                    'title' => $sport->value,
                    'class' => 'sport-link sport-link-' . $sport->id
            ));
            ?>
        </div>
        <div class="media-body">
            <h3 class="media-heading"><?php
                echo CHtml::link($sport->value, '/sport/' . $sport->url, array(
                        'title' => $sport->value,
                        'class' => 'sport-link sport-link-' . $sport->id
                    ));
            ?></h3>
            <div class="media">
            <?php
            echo $sport->description . "<br />\n";
            $url_text = $sport->value . ': описание вида спорта, расписание игр';
            echo CHtml::link($url_text, '/sport/' . $sport->url, array('title' => $url_text));
            ?></div>
        </div>
    </div>
<?php
} // foreach


