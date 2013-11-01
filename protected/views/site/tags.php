<?php
/* @var $this SiteController */
/* @var $tags array */

$this->breadcrumbs = array(
    'Теги записей'
);
$this->buildMetaTitle();

foreach ($tags as $tag) {
    echo CHtml::link($tag->value, '/tag/' . $tag->url, array(
                'title' => $tag->value,
                'class' => 'tag-link tag-link-' . $tag->id
            )
        ) . '&nbsp;';
}


