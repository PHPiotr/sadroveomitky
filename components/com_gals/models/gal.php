<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_gals
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

/**
 * Youtube model for the Joomla Youtubes component.
 *
 * @package     Joomla.Site
 * @subpackage  com_gals
 * @since       1.5
 */
class GalsModelGal extends JModelLegacy {

	protected $_item;
	protected $_alias;
	protected $_cache;

	/**
	 * Get the data for a gallery.
	 * @return  object
	 */
	public function &getItem() {

		if (!isset($this->_item)) {
			$this->_cache = JFactory::getCache('com_gals', '');

			JURI::current();
			$router = & JSite::getRouter();
			$query = $router->parse(JURI::getInstance());

			$alias = JFilterOutput::stringURLSafe($query['alias']);
			$db = $this->getDbo();
			$query = $db->getQuery(true)->select('id')->from('#__gals')->where('alias = ' . $db->q($alias));
			$db->setQuery($query, 0, 1);
			$db->execute();
			$id = (int) $db->loadResult();

			$this->_item = $this->_cache->get($id);

			$this->_alias = $alias;

			if ($this->_item === false) {
				$db = $this->getDbo();
				$query = $db->getQuery(true)->select('id, title, alias, mainphoto')->from('#__gals')->where('alias="' . $alias . '"');

				$db->setQuery($query);

				try {
					$db->execute();
				} catch (RuntimeException $e) {
					JError::raiseError(500, $e->getMessage());
				}

				$this->_item = $db->loadObject();
				if (isset($this->_item->id)) {
					$this->_cache->store($this->_item, $this->_item->id);
				} else {
					JError::raiseError(404, JText::_('NO_GALLERY'));
					return false;
				}
			}
		}
		return $this->_item;
	}

	public function &getPhotos() {

//		$this->_files = $this->_cache->get($this->_item->id);
//		if (!$this->_files) {
		$db = $this->getDbo();
		$query = $db->getQuery(true)
				->select('gp.id_gal, gp.photo')
				->from('#__gals g')
				->join('left', '#__gals_photo gp ON g.id = gp.id_gal')
				->where('g.alias = ' . $db->quote($this->_alias))
				->order('gp.id');
		$db->setQuery($query);
		try {
			$db->execute();
		} catch (RuntimeException $e) {
			JError::raiseError(500, $e->getMessage());
		}
		$this->_files = $db->loadObjectList();
//			$this->_cache->store($this->_files, $this->_item->id);
//		}

		return $this->_files;
	}

}
