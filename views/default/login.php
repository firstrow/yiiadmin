<?php
    $this->pageTitle=$title;
?>
<div class="form login">
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<p><?php echo YiiadminModule::t( 'Пожалуйста, укажите пароль.'); ?></p>

	<?php echo $form->passwordField($model,'password'); ?>
	<?php echo $form->error($model,'password'); ?>

	<?php echo CHtml::submitButton('Enter'); ?>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php
// vim: set filetype=php expandtab tabstop=4 shiftwidth=4 autoindent smartindent:
?>
