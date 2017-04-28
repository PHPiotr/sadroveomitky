<?php

defined('_JEXEC') || die('Restricted access');

class modYoutubesHelper {

	function getYoutube($params) {

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select($db->quoteName('link'))
				->select($db->quoteName('title'))
				->from($db->quoteName('#__youtubes'))
				->where('language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
				->order('id DESC');
		$db->setQuery($query, 0, 1);
		$result = $db->loadAssoc();
		return !empty($result) ? $result : null;
	}

}