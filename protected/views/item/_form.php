<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'item-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Code'); ?>
		<?php echo $form->textField($model,'Code',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'Code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Name'); ?>
		<?php echo $form->textField($model,'Name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Brand'); ?>
		<?php echo $form->textField($model,'Brand',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'Brand'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Unit'); ?>
		<?php echo $form->textField($model,'Unit',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'Unit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Unit2'); ?>
		<?php echo $form->textField($model,'Unit2',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'Unit2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Branch'); ?>
		<?php echo $form->textField($model,'Branch',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'Branch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Line'); ?>
		<?php echo $form->textField($model,'Line',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'Line'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Country'); ?>
		<?php echo $form->textField($model,'Country',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'Country'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ArtType'); ?>
		<?php echo $form->textField($model,'ArtType',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'ArtType'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->