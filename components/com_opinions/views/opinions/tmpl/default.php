<?php

defined('_JEXEC') or die;
JHTML::script($this->script);
?>
<div class="reference-component moduletable profishop-contact">
	<h3><?php echo JText::_('COM_OPINIONS_CONTACT_FORM'); ?></h3>
	<form name="email" class="form-horizontal profishop-contact" id="contact-form" method="post" action="<?php echo JRoute::_('index.php?option=com_opinions&task=send', false); ?>">
		<?php echo $this->form->getInput('inputSubject', null, $this->session->get('inputSubject')); ?>

		<div class="control-group">
			<?php echo $this->form->getLabel('inputName'); ?>
			<div class="controls">
				<?php echo $this->form->getInput('inputName', null, trim($this->session->get('inputName'))); ?>
			</div>
		</div>

		<div class="control-group">
			<?php echo $this->form->getLabel('inputCity'); ?>
			<div class="controls">
				<?php echo $this->form->getInput('inputCity', null, trim($this->session->get('inputCity'))); ?>
			</div>
		</div>

		<div class="control-group">
			<?php echo $this->form->getLabel('inputPhone'); ?>
			<div class="controls">
				<?php echo $this->form->getInput('inputPhone', null, $this->session->get('inputPhone')); ?>
			</div>
		</div>

		<div class="control-group">
			<?php echo $this->form->getLabel('inputEmail'); ?>
			<div class="controls">
				<?php echo $this->form->getInput('inputEmail', null, $this->session->get('inputEmail')); ?>
			</div>
		</div>

		<?php echo $this->form->getLabel('inputMessage'); ?>
		<?php echo $this->form->getInput('inputMessage', null, $this->session->get('inputMessage')); ?>

		<div class="clearfix" style="height: 15px"></div>
		<button name="submit" type="submit" class="submission btn submit pull-right"><?php echo JText::_('COM_OPINIONS_SUBMIT'); ?> <span class="icon-arrow-right"></span></button>
		<div class="clearfix"></div>

		<?php echo JHtml::_('form.token'); ?>
    </form>
</div>