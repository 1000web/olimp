<?php
/* @var $this SiteController */
/* @var $tag array */

$this->breadcrumbs = array(
    'Теги записей' => array('/tag'),
    $tag->value
);
$this->buildMetaTitle();

echo "<h2>" . $tag->value . "</h2>";

