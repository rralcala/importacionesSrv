<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Date')); ?>:</b>
	<?php echo CHtml::encode($data->Date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PeriodStart')); ?>:</b>
	<?php echo CHtml::encode($data->PeriodStart); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PeriodEnd')); ?>:</b>
	<?php echo CHtml::encode($data->PeriodEnd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SubPeriod')); ?>:</b>
	<?php echo CHtml::encode($data->SubPeriod); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Enabled')); ?>:</b>
	<?php echo CHtml::encode($data->Enabled); ?>
	<br />


</div>