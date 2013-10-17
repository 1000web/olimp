<?php
/* @var $this SiteController */
/* @var $categories array */


foreach ($categories as $item) {
    echo CHtml::link($item->value, '/category/' .$item->id . '/' . $item->url, array(
                'title' => $item->value,
                'class' => 'cat-link cat-link-' . $item->id
            )
        ) . '&nbsp;';
}


