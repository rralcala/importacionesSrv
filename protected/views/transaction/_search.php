<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Oper'); ?>
		<?php echo $form->textField($model,'Oper'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'item_id'); ?>
		<?php echo $form->textField($model,'item_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Qty'); ?>
		<?php echo $form->textField($model,'Qty',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Unit'); ?>
		<?php echo $form->textField($model,'Unit',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RowTotal'); ?>
		<?php echo $form->textField($model,'RowTotal',array('size'=>14,'maxlength'=>14)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TransDate'); ?>
		<?php echo $form->textField($model,'TransDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'OperDate'); ?>
		<?php echo $form->textField($model,'OperDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'StockDepo'); ?>
		<?php echo $form->textField($model,'StockDepo',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Currency'); ?>
		<?php echo $form->textField($model,'Currency',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CurrencyRate'); ?>
		<?php echo $form->textField($model,'CurrencyRate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Enable'); ?>
		<?php echo $form->textField($model,'Enable'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->