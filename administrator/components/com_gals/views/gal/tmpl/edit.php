<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gals
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'gal.cancel' || document.formvalidator.isValid(document.id('gal-form')))
		{
			Joomla.submitform(task, document.getElementById('gal-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gals&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="gal-form" class="form-validate form-horizontal">

	<?php echo JLayoutHelper::render('joomla.edit.item_title', $this); ?>

	<!-- Begin Banner -->
	<div class="span10 form-horizontal">

		<fieldset>
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_GALS_GAL_DETAILS', true)); ?>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('title'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('title'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('alias'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('alias'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('mainphoto'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('mainphoto'); ?>
				</div>
			</div>

			<?php if ($this->item->id !== null): ?>
				<div class="well">
					<?php foreach ($this->photos as $sPhoto): ?>
						<?php $sMainClass = ($sPhoto['photo'] === $this->item->mainphoto) ? ' main' : ' normal'; ?>
						<div class="photos">
							<img class="photo <?php echo $sMainClass; ?>" src="../images/galleries/<?php echo $this->item->alias; ?>/min/<?php echo $sPhoto['photo']; ?>" alt="<?php echo $sPhoto['photo']; ?>" title="<?php echo $sPhoto['photo']; ?>" />
							<input id="<?php echo $sPhoto['id']; ?>" class="remove" type="checkbox" name="remove[]" value="<?php echo $sPhoto['photo']; ?>" />
						</div>
					<?php endforeach; ?>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>

			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'other', JText::_('COM_GALS_GROUP_LABEL_MINIATURES_DETAILS', true)); ?>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('width'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('width'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('height'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('height'); ?>
				</div>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		</fieldset>

		<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<!-- End Newsfeed -->
	<!-- Begin Sidebar -->
	<div class="span2">
		<h4><?php echo JText::_('JDETAILS'); ?></h4>
		<hr />
		<fieldset class="form-vertical">

			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('state'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('state'); ?>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('language'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('language'); ?>
				</div>
			</div>
		</fieldset>
	</div>
	<!-- End Sidebar -->
</form>