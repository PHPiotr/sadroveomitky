<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_youtubes
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * HTML View class for the Youtubes component
 *
 * @package     Joomla.Site
 * @subpackage  com_youtubes
 * @since       3.2
 */
class SitemapViewSitemap extends JViewLegacy {

	public function display($tpl = null) {
		$app = JFactory::getApplication();

		$params = $app->getParams();

		// Check for layout override
		$active = JFactory::getApplication()->getMenu()->getActive();

		if (isset($active->query['layout'])) {
			$this->setLayout($active->query['layout']);
		}

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
		$this->params = &$params;

		$metaDesc = $this->params->get('menu-meta_description');
		if (!empty($metaDesc)) {
			$document = & JFactory::getDocument();
			$document->setDescription($metaDesc);
		}

		$metaKeys = $this->params->get('menu-meta_keywords');
		if (!empty($metaKeys)) {
			$document = & JFactory::getDocument();
			$document->setMetaData('keywords', $metaKeys);
		}

		$this->items = $this->get('Items');
		$langTag = JFactory::getLanguage()->getTag();
		$this->lang = $langTag == 'pl-PL' ? 'pl/' : '';

		$this->_setTitle();

		parent::display($tpl);
	}

	protected function _setTitle() {

		$app = &JFactory::getApplication();
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
	}

}
