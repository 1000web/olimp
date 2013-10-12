<div class="span11">
    <?php
    $this->buildFilterButton(Menu::model()->getOptions(), 'menu');
    $this->buildFilterButton(MenuItem::model()->getOptions('parent_id', 'parent_id'), 'menu_parent_id');
    ?>
</div>
