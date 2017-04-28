<?php

defined('_JEXEC') || die('Restricted access');

class modGalsHelper {

	function getGals($params) {

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select($db->quoteName('mainphoto'))
				->select($db->quoteName('title'))
				->select($db->quoteName('alias'))
				->from($db->quoteName('#__gals'))
				->where('language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
				->order('id DESC');
		$db->setQuery($query, 0, 1);
		$result = $db->loadAssoc();
		return !empty($result) ? $result : null;
	}

	public function getAlias() {
		$langTag = JFactory::getApplication()->getLanguage()->getTag();
		$db = & JFactory::getDbo();
		$query = $db->getQuery(true)
				->select($db->qn('path'))
				->from('#__menu')
				->where('link = ' . $db->q('index.php?option=com_gals&view=gals') . ' AND client_id = 0 AND language = ' . $db->q($langTag));
		$db->setQuery($query, 0, 1);
		$db->execute();
		$result = '';
		if ($langTag == 'pl-PL') {
			$result .= 'pl/';
		}
		$result .= $db->loadResult();

		return $result;
	}

}
