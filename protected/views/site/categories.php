<?php
/* @var $this SiteController */
/* @var $categories array */

$this->breadcrumbs = array(
    'Категории записей',
);
$this->buildMetaTitle();

foreach ($categories as $item) {
    echo CHtml::link($item->value, '/category/' . $item->url, array(
                'title' => $item->value,
                'class' => 'cat-link cat-link-' . $item->id
            )
        ) . '&nbsp;';
}


