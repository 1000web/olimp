<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
    <div class="row">
        <div class="span10">
            <div id="content">
                <?php echo $content; ?>
            </div>
            <!-- content -->
        </div>
        <div class="span2">
            <div id="sidebar">
                <?php
                // если не пустое меню, то показываем
                if ($this->menu) {
                    $this->widget('bootstrap.widgets.TbMenu', array(
                        'type' => 'list',
                        'items' => CMap::mergeArray(
                            array(array('label' => 'Операции')),
                            $this->menu
                        ),
                    ));
                }
                ?>
            </div>
            <!-- sidebar -->
        </div>
    </div>
<?php $this->endContent(); ?>