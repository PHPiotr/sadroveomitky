<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * youtubes master display controller.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 * @since       1.6
 */
class YoutubesController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/youtubes.php';
		youtubesHelper::updateReset();

		$view   = $this->input->get('view', 'youtubes');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'youtube' && $layout == 'edit' && !$this->checkEditId('com_youtubes.edit.youtube', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_youtubes&view=youtubes', false));
			return false;
		}

		parent::display();

		return $this;
	}
}
