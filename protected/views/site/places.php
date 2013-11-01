<?php
/* @var $this SiteController */
/* @var $places array */

$this->breadcrumbs = array(
    'Место проведения'
);
$this->buildMetaTitle();

?>
<h2>Место проведения зимних Олимпийских Игр 2014 в Сочи</h2>

<!--p>Игры в Сочи войдут в историю зимних Олимпийских и Паралимпийских игр как самые компактные. Добраться от одного объекта
до другого можно будет за несколько минут. Все соревнования Паралимпийских игр пройдут на тех же объектах, что и олимпийские.
Поэтому спортивные сооружения Игр в Сочи строятся с учетом нужд людей с инвалидностью.</p>

<p>Для проведения Игр в Сочи построят 11 спортивных объектов. Они будут расположены в двух кластерах – горном и прибрежном,
расстояние между которыми составит 48 км.</p>

<p>Время в пути от горных спортивных объектов до прибрежных займет не более 30 минут по новой железной дороге.</p>

<p>В каждом кластере будет Олимпийская деревня. В прибрежном кластере путь из Олимпийской деревни до места проведения
соревнований займет не более 5 минут, а в горном кластере - не более 15 минут.</p-->

<?php

$h3 = '';
foreach ($places as $place) {
    if($h3 != $place['placegroup']['value']) {
        $h3 = $place['placegroup']['value'];
        echo "<h3>" . $place['placegroup']['value'] . "</h3>\n";
        echo "<p><small>" . $place['placegroup']['description'] . "</small></p>\n";
    }
    /*
    echo CHtml::link($place['value'], '/place/' . $place['url'], array(
                'title' => $place['value'],
                'class' => 'place-link place-link-' . $place['id']
            )
        ) . '<br />';/**/




?>

<div class="media">
        <div class="pull-left">
            <?php
            $img = MyHelper::insertImage(Yii::app()->theme->baseUrl . '/images/place/' . $place->url . '-preview.jpg', $place->value, array('class'=>'media-object'));
            echo CHtml::link($img, '/place/' . $place->url, array(
                    'title' => $place->value,
                    'class' => 'place-link place-link-' . $place->id
                ));
            ?>
</div>
<div class="media-body">
    <h3 class="media-heading"><?php
        echo CHtml::link($place->description, '/place/' . $place->url, array(
                'title' => $place->description,
                'class' => 'place-link place-link-' . $place->id
            ));
        ?></h3>
    <div class="media">
        <?php
        //echo $place->description . "<br />\n";
        $url_text = $place->value . ': описание объекта Олимпийских Игр';
        echo CHtml::link($url_text, '/place/' . $place->url, array('title' => $url_text));
        ?></div>
</div>
</div>

<?php
}

?>
<br /><br />