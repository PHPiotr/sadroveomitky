<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('YoutubesHelper', JPATH_COMPONENT . '/helpers/youtubes.php');

/**
 * View to edit a banner.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 * @since       1.5
 */
class YoutubesViewYoutube extends JViewLegacy {

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
		$this->state = $this->get('State');

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
		$canDo = YoutubesHelper::getActions(0);

		JToolbarHelper::title($isNew ? JText::_('COM_YOUTUBES_MANAGER_YOUTUBE_NEW') : JText::_('COM_YOUTUBES_MANAGER_YOUTUBE_EDIT'), 'youtubes.png');

		// If not checked out, can save the item.
		if (($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_youtubes', 'core.create')) > 0)) {
			JToolbarHelper::apply('youtube.apply');
			JToolbarHelper::save('youtube.save');

			if ($canDo->get('core.create')) {
				JToolbarHelper::save2new('youtube.save2new');
			}
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolbarHelper::save2copy('youtube.save2copy');
		}

		if (empty($this->item->id)) {
			JToolbarHelper::cancel('youtube.cancel');
		} else {
			JToolbarHelper::cancel('youtube.cancel', 'JTOOLBAR_CLOSE');
		}
	}

}
