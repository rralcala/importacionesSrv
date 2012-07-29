<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'request-detail-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'request_id'); ?>
		<?php echo $form->textField($model,'request_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'request_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_id'); ?>
		<?php echo $form->textField($model,'item_id'); ?>
		<?php echo $form->error($model,'item_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'StockTime'); ?>
		<?php echo $form->textField($model,'StockTime',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'StockTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ShipTime'); ?>
		<?php echo $form->textField($model,'ShipTime',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ShipTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ManualQty'); ?>
		<?php echo $form->textField($model,'ManualQty',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ManualQty'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->