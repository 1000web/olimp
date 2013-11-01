<?php
/* @var $this SiteController */
/* @var $category array */

$this->breadcrumbs = array(
    'Категории записей' => array('/category'),
    $category->value
);
$this->buildMetaTitle();

echo "<h3>" . $category->value . "</h3>";

