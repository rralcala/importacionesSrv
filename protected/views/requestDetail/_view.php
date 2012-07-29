<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_id')); ?>:</b>
	<?php echo CHtml::encode($data->request_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_id')); ?>:</b>
	<?php echo CHtml::encode($data->item_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('StockTime')); ?>:</b>
	<?php echo CHtml::encode($data->StockTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ShipTime')); ?>:</b>
	<?php echo CHtml::encode($data->ShipTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ManualQty')); ?>:</b>
	<?php echo CHtml::encode($data->ManualQty); ?>
	<br />


</div>