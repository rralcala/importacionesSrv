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
		<?php echo $form->label($model,'Date'); ?>
		<?php echo $form->textField($model,'Date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PeriodStart'); ?>
		<?php echo $form->textField($model,'PeriodStart'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PeriodEnd'); ?>
		<?php echo $form->textField($model,'PeriodEnd'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SubPeriod'); ?>
		<?php echo $form->textField($model,'SubPeriod'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Enabled'); ?>
		<?php echo $form->textField($model,'Enabled',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->