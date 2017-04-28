<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_galleries
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * youtubes component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_galleries
 * @since       1.6
 */
class GalsHelper {

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
			$assetName = 'com_gals';
			$level = 'component';
		} else {
			$assetName = 'com_gals.category.' . (int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_gals', $level);
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
				->from('#__gals');
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
