<div class="span11">
    <?php
    $this->buildFilterButton(Item::model()->getOptions('parent_id', 'parent_id'), 'item_parent_id');
    $this->buildFilterButton(Item::model()->getOptions('module', 'module'), 'item_module');
    $this->buildFilterButton(Item::model()->getOptions('controller', 'controller'), 'item_controller');
    $this->buildFilterButton(Item::model()->getOptions('action', 'action'), 'item_action');
    ?>
</div>
