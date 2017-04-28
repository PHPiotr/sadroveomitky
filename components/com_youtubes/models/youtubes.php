<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_youtubes
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

/**
 * youtubes model for the Joomla youtubes component.
 *
 * @package     Joomla.Site
 * @subpackage  com_youtubes
 * @since       1.6
 */
class YoutubesModelYoutubes extends JModelList {

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id    A prefix for the store id.
	 *
	 * @return  string  A store id.
	 * @since   1.6
	 */
	protected function getStoreId($id = '') {
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		return parent::getStoreId($id);
	}

	/**
	 * Gets a list of youtubes
	 *
	 * @return  array  An array of banner objects.
	 * @since   1.6
	 */
	protected function getListQuery() {  
		$app = JFactory::getApplication();
		$params = $app->getParams('com_youtubes');
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('link, title')->from('#__youtubes'); 

		// Filter by a single or group of categories
		// Filter by language
//		if ($this->getState('filter.language')) {
		$query->where('language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
//		}
		$query->where('state = 1');
		$query->order($params->get('ordering'));
		return $query;
	}

	/**
	 * Get a list of movies.
	 *
	 * @return  array
	 * @since   1.6
	 */
	public function getItems() {
		if (!isset($this->cache['items'])) {
			$this->cache['items'] = parent::getItems();

			if (isset($item->params)) {
				foreach ($this->cache['items'] as &$item) {
					$parameters = new JRegistry;
					$parameters->loadString($item->params);
					$item->params = $parameters;
				}
			}
		}
		return $this->cache['items'];
	}

}
