<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Code')); ?>:</b>
	<?php echo CHtml::encode($data->Code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Name')); ?>:</b>
	<?php echo CHtml::encode($data->Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Brand')); ?>:</b>
	<?php echo CHtml::encode($data->Brand); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Unit')); ?>:</b>
	<?php echo CHtml::encode($data->Unit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Unit2')); ?>:</b>
	<?php echo CHtml::encode($data->Unit2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Branch')); ?>:</b>
	<?php echo CHtml::encode($data->Branch); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Line')); ?>:</b>
	<?php echo CHtml::encode($data->Line); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Country')); ?>:</b>
	<?php echo CHtml::encode($data->Country); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ArtType')); ?>:</b>
	<?php echo CHtml::encode($data->ArtType); ?>
	<br />

	*/ ?>

</div>