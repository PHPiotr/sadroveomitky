<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_gals
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('YoutubesHelper', JPATH_COMPONENT . '/helpers/gals.php');

/**
 * View to edit a banner.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_gals
 * @since       1.5
 */
class GalsViewGal extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		// Initialiase variables.
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->photos = $this->get('Photos');
		$this->state = $this->get('State');

		JHtml::stylesheet('media/com_gals/css/gals.css');
		JHtml::_('jquery.framework');
		JHtml::script('media/com_gals/js/gals.js');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar() {
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user = JFactory::getUser();
		$userId = $user->get('id');
		$isNew = ($this->item->id == 0);

		// Since we don't track these assets at the item level, use the category id.
		$canDo = GalsHelper::getActions(0);

		JToolbarHelper::title($isNew ? JText::_('COM_GALS_MANAGER_GALS_NEW') : JText::_('COM_GALS_MANAGER_GALS_EDIT'), 'gals.png');

		// If not checked out, can save the item.
		if (($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_gals', 'core.create')) > 0)) {
			JToolbarHelper::apply('gal.apply');
			JToolbarHelper::save('gal.save');

			if ($canDo->get('core.create')) {
				JToolbarHelper::save2new('gal.save2new');
			}
		}

		if (empty($this->item->id)) {
			JToolbarHelper::cancel('gal.cancel');
		} else {
			JToolbarHelper::cancel('gal.cancel', 'JTOOLBAR_CLOSE');
		}
	}

}
