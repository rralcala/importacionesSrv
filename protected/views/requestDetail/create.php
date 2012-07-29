<?php
$this->breadcrumbs=array(
	'Request Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RequestDetail', 'url'=>array('index')),
	array('label'=>'Manage RequestDetail', 'url'=>array('admin')),
);
?>

<h1>Create RequestDetail</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>