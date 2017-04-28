<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_gals
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * galleries list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_gals
 * @since       1.6
 */
class GalsControllerGals extends JControllerAdmin {

	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_GALS_GALS';

	/**
	 * Constructor.
	 *
	 * @param   array An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 * @since   1.6
	 */
	public function getModel($name = 'Gal', $prefix = 'GalsModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	/**
	 * Removes an item.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	public function delete() {

		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		// begin handle files and folders deleting
		jimport('joomla.filesystem.folder');

		$sIn = implode(',', $cid);

		$db = JFactory::getDBO();
		$db->setQuery("DELETE FROM #__gals_photo WHERE id_gal IN ($sIn)")->query();

		$db = JFactory::getDBO();
		$db->setQuery("SELECT title FROM #__gals WHERE id IN ($sIn)");
		$aResult = $db->loadObjectList();

		$aAliases = array();

		foreach ($aResult as $val) {
			$aAliases[] = JFilterOutput::stringURLSafe($val->title);
		}

		foreach ($aAliases as $val) {
			JFolder::delete(JPATH_ROOT . DS . 'images' . DS . 'galleries' . DS . $val);
		}
		// end handle files and folders deleting

		if (!is_array($cid) || count($cid) < 1) {
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		} else {
			// Get the model.
			$model = $this->getModel();
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->delete($cid)) {
				$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			} else {
				$this->setMessage($model->getError());
			}
		}
		// Invoke the postDelete method to allow for the child class to access the model.
		$this->postDeleteHook($model, $cid);

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}

}
