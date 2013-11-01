<?php
/* @var $this SiteController */
/* @var $place array */

$this->breadcrumbs = array(
    'Место проведения' => array('/place'),
    $place->value
);
$this->buildMetaTitle();

?>
<div class="media">
    <div class="pull-left">
        <?php
            echo MyHelper::insertImage(Yii::app()->theme->baseUrl . '/images/place/' . $place->url . '-preview.jpg', $place->description, array('class'=>'media-object'));
        ?>
</div>
<div class="media-body">
    <h3 class="media-heading"><?php echo $place->description; ?></h3>
    <div class="media"><?php
        if(!empty($place->mest)) echo "<p><strong>Вместимость комплекса:</strong> " . $place->use_after . "</p>\n";
        if(!empty($place->year)) echo "<p><strong>Начало использования:</strong> " . $place->year . "</p>\n";
        if(!empty($place->use_after)) echo "<p><strong>Использование после Игр:</strong> " . $place->mest . "</p>\n";
    ?></div>
</div>
</div>

<p><?php echo $place->description1; ?></p>
