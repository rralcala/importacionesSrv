<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'request-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Date'); ?>
		<?php echo $form->textField($model,'Date'); ?>
		<?php echo $form->error($model,'Date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PeriodStart'); ?>
		<?php echo $form->textField($model,'PeriodStart'); ?>
		<?php echo $form->error($model,'PeriodStart'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PeriodEnd'); ?>
		<?php echo $form->textField($model,'PeriodEnd'); ?>
		<?php echo $form->error($model,'PeriodEnd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SubPeriod'); ?>
		<?php echo $form->textField($model,'SubPeriod'); ?>
		<?php echo $form->error($model,'SubPeriod'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Enabled'); ?>
		<?php echo $form->textField($model,'Enabled',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'Enabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->