<?php
$this->breadcrumbs=array(
	'Request Details',
);

$this->menu=array(
	array('label'=>'Create RequestDetail', 'url'=>array('create')),
	array('label'=>'Manage RequestDetail', 'url'=>array('admin')),
);
?>

<h1>Request Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
