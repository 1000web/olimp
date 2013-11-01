<?php
/* @var $this SiteController */
/* @var $sport array */

$this->breadcrumbs = array(
    'Виды спорта' => array('/sport'),
    $sport->value
);
$this->buildMetaTitle();


echo "<a id='top'> </a><h2>" . $sport->value . "</h2>\n";
?>
<div class="media">
    <div class="pull-left">
        <?php
            echo MyHelper::insertImage(Yii::app()->theme->baseUrl . '/images/olimpic-games/' . $sport->url . '.jpg', $sport->value, array('class'=>'media-object'));
        ?>
    </div>
    <div class="media-body">
        <h3 class="media-heading"><?php echo $sport->value; ?></h3>
        <div class="media"><?php echo $sport->description; ?></div>
    </div>
</div>

<?php
echo '<ul>';
$i = 1;
foreach($text as $t) {
    echo "<li><a href='#" . $sport->url . "-" . ($i++) . "'>" . $t->header . "</a></li>\n";
}
echo '</ul>';

$i = 1;
foreach($text as $t) {
    $link = "Наверх страницы &laquo;" . $sport->value . "&raquo;";
    echo "<h4>" . $t->header . "</h4>\n";
    echo "<p>" . $t->content . "<br />\n";
    echo "<a id='" . $sport->url . "-" . ($i++) . "' href='#top' title='" . $link . "'>Наверх</a></p>\n";
}
