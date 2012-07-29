<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Oper')); ?>:</b>
	<?php echo CHtml::encode($data->Oper); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_id')); ?>:</b>
	<?php echo CHtml::encode($data->item_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Qty')); ?>:</b>
	<?php echo CHtml::encode($data->Qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Unit')); ?>:</b>
	<?php echo CHtml::encode($data->Unit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RowTotal')); ?>:</b>
	<?php echo CHtml::encode($data->RowTotal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TransDate')); ?>:</b>
	<?php echo CHtml::encode($data->TransDate); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('OperDate')); ?>:</b>
	<?php echo CHtml::encode($data->OperDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('StockDepo')); ?>:</b>
	<?php echo CHtml::encode($data->StockDepo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Currency')); ?>:</b>
	<?php echo CHtml::encode($data->Currency); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CurrencyRate')); ?>:</b>
	<?php echo CHtml::encode($data->CurrencyRate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Enable')); ?>:</b>
	<?php echo CHtml::encode($data->Enable); ?>
	<br />

	*/ ?>

</div>