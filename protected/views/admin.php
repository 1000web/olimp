<?php
/* @var $this Controller */
/* @var $this ->_model Model */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#admin-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>
    Можно использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> или
    <b>=</b>) в начале параметра поиска.</p>
<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
    <div class="search-form" style="display:none">
        <?php $this->renderPartial('/' . $this->id . '/_search', array('model' => $this->_model)); ?>
    </div><!-- search-form -->


<?php
$columns = $this->_model->getAvailableAttributes();
$columns[] = array('class' => 'bootstrap.widgets.TbButtonColumn');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'admin-grid',
    'dataProvider' => $this->_model->search(),
    'filter' => $this->_model,
    'type' => 'striped bordered condensed',
    'template' => '{summary}{items}{pager}',
    'enablePagination' => true,
    'columns' => $columns,
    'pager' => array(
        //'maxButtonCount' => Yii::app()->controller->isMobile?4:10,
        'maxButtonCount' => 10,
        'class' => 'bootstrap.widgets.TbPager',
    ),
));
