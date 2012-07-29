<?php
$this->breadcrumbs=array(
	'Request Details'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List RequestDetail', 'url'=>array('index')),
	array('label'=>'Create RequestDetail', 'url'=>array('create')),
	array('label'=>'Update RequestDetail', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RequestDetail', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RequestDetail', 'url'=>array('admin')),
);
?>

<h1>View RequestDetail #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'request_id',
		'item_id',
		'StockTime',
		'ShipTime',
		'ManualQty',
	),
)); ?>
