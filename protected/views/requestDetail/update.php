<?php
$this->breadcrumbs=array(
	'Request Details'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RequestDetail', 'url'=>array('index')),
	array('label'=>'Create RequestDetail', 'url'=>array('create')),
	array('label'=>'View RequestDetail', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RequestDetail', 'url'=>array('admin')),
);
?>

<h1>Update RequestDetail <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>