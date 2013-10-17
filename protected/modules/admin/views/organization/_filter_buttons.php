<div class="span11">
    <?php
    $this->buildFilterButton(OrganizationType::model()->getOptions(), 'organization_type_id');
    $this->buildFilterButton(OrganizationRegion::model()->getOptions(), 'organization_region_id');
    $this->buildFilterButton(OrganizationGroup::model()->getOptions(), 'organization_group_id');
    ?>
</div>
