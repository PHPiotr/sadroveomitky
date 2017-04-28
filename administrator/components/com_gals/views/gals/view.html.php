<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  COM_GALS
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * View class for a list of gals.
 *
 * @package     Joomla.Administrator
 * @subpackage  COM_GALS
 * @since       1.6
 */
class GalsViewGals extends JViewLegacy {

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

		JHtml::stylesheet(JUri::root() . 'media/com_gals/fancyapps/source/jquery.fancybox.css');
		JHtml::_('jquery.framework');
		JHtml::script(JUri::root() . "media/com_gals/fancyapps/source/jquery.fancybox.pack.js");
		JHtml::script(JUri::root() . "media/com_gals/js/gal.js");

		$this->categories = $this->get('CategoryOrders');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

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
		require_once JPATH_COMPONENT . '/helpers/gals.php';
		$canDo = GalsHelper::getActions($this->state->get('filter.category_id'));

		$user = JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_GALS_MANAGER_GALS'), 'gals.png');
//		if (count($user->getAuthorisedCategories('COM_GALS', 'core.create')) > 0)
//		{
		JToolbarHelper::addNew('gal.add');
//		}

		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('gal.edit');
		}

		if ($canDo->get('core.edit.state')) {
			if ($this->state->get('filter.state') != 2) {
				JToolbarHelper::publish('gals.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('gals.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($this->state->get('filter.state') != -1) {
				if ($this->state->get('filter.state') != 2) {
					JToolbarHelper::archiveList('gals.archive');
				} elseif ($this->state->get('filter.state') == 2) {
					JToolbarHelper::unarchiveList('gals.publish');
				}
			}
		}

		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::checkin('gals.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolbarHelper::deleteList('', 'gals.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('gals.trash');
		}

		JHtmlSidebar::setAction('index.php?option=com_gals&view=gals');

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
			'title' => JText::_('COM_GALS_HEADING_TITLE'),
			'language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'id' => JText::_('JGRID_HEADING_ID')
		);
	}

}
