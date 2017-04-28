<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  COM_YOUTUBES
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * View class for a list of youtubes.
 *
 * @package     Joomla.Administrator
 * @subpackage  COM_YOUTUBES
 * @since       1.6
 */
class YoutubesViewYoutubes extends JViewLegacy {

	protected $categories;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null) {
		$this->categories = $this->get('CategoryOrders');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		YoutubesHelper::addSubmenu('youtubes');

		$this->addToolbar();

		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar() {
		require_once JPATH_COMPONENT . '/helpers/youtubes.php';
		$canDo = YoutubesHelper::getActions($this->state->get('filter.category_id'));

		$user = JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_YOUTUBES_MANAGER_YOUTUBES'), 'youtubes.png');
//		if (count($user->getAuthorisedCategories('COM_YOUTUBES', 'core.create')) > 0)
//		{
		JToolbarHelper::addNew('youtube.add');
//		}

		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('youtube.edit');
		}

		if ($canDo->get('core.edit.state')) {
			if ($this->state->get('filter.state') != 2) {
				JToolbarHelper::publish('youtubes.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('youtubes.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($this->state->get('filter.state') != -1) {
				if ($this->state->get('filter.state') != 2) {
					JToolbarHelper::archiveList('youtubes.archive');
				} elseif ($this->state->get('filter.state') == 2) {
					JToolbarHelper::unarchiveList('youtubes.publish');
				}
			}
		}

		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::checkin('youtubes.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolbarHelper::deleteList('', 'youtubes.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('youtubes.trash');
		}

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('COM_YOUTUBES');
		}

		JHtmlSidebar::setAction('index.php?option=com_youtubes&view=youtubes');

		JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_state', JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

		JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_LANGUAGE'), 'filter_language', JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields() {
		return array(
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'state' => JText::_('JSTATUS'),
			'title' => JText::_('COM_YOUTUBES_HEADING_TITLE'),
			'link' => JText::_('COM_YOUTUBES_HEADING_LINK'),
			'language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'id' => JText::_('JGRID_HEADING_ID')
		);
	}

	public function getIdFromLink($sLink) {
		$regExp = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
		preg_match($regExp, $sLink, $match);
		if ($match && strlen($match[2]) == 11) {
			return $match[2];
		} else {
			return null;
		}
	}

}
