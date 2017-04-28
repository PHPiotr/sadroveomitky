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
 * youtubes component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 * @since       1.6
 */
class YoutubesHelper {

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string	The name of the active view.
	 *
	 * @return  void
	 * @since   1.6
	 */
	public static function addSubmenu($vName) {
		JHtmlSidebar::addEntry(
				JText::_('COM_YOUTUBES_SUBMENU_YOUTUBES'), 'index.php?option=com_youtubes&view=youtubes', $vName == 'youtubes'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The category ID.
	 *
	 * @return  JObject
	 * @since   1.6
	 */
	public static function getActions($categoryId = 0) {

		$user = JFactory::getUser();
		$result = new JObject;

		if (empty($categoryId)) {
			$assetName = 'com_youtubes';
			$level = 'component';
		} else {
			$assetName = 'com_youtubes.category.' . (int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_youtubes', $level);

		foreach ($actions as $action) {
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}

	/**
	 * @return  boolean
	 * @since   1.6
	 */
	public static function updateReset() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select('*')
				->from('#__youtubes');
		$db->setQuery($query);

		try {
			$db->loadObjectList();
		} catch (RuntimeException $e) {
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}

		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

		return true;
	}

}
