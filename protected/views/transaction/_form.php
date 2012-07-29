<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'transaction-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Oper'); ?>
		<?php echo $form->textField($model,'Oper'); ?>
		<?php echo $form->error($model,'Oper'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_id'); ?>
		<?php echo $form->textField($model,'item_id'); ?>
		<?php echo $form->error($model,'item_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Qty'); ?>
		<?php echo $form->textField($model,'Qty',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'Qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Unit'); ?>
		<?php echo $form->textField($model,'Unit',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'Unit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'RowTotal'); ?>
		<?php echo $form->textField($model,'RowTotal',array('size'=>14,'maxlength'=>14)); ?>
		<?php echo $form->error($model,'RowTotal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TransDate'); ?>
		<?php echo $form->textField($model,'TransDate'); ?>
		<?php echo $form->error($model,'TransDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'OperDate'); ?>
		<?php echo $form->textField($model,'OperDate'); ?>
		<?php echo $form->error($model,'OperDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'StockDepo'); ?>
		<?php echo $form->textField($model,'StockDepo',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'StockDepo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Currency'); ?>
		<?php echo $form->textField($model,'Currency',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'Currency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CurrencyRate'); ?>
		<?php echo $form->textField($model,'CurrencyRate'); ?>
		<?php echo $form->error($model,'CurrencyRate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Enable'); ?>
		<?php echo $form->textField($model,'Enable'); ?>
		<?php echo $form->error($model,'Enable'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->