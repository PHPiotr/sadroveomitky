<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_gals
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * HTML View class for the Galleries component
 *
 * @package     Joomla.Site
 * @subpackage  com_gals
 * @since       1.0
 */
class GalsViewGals extends JViewLegacy {

	public function display($tpl = null) {
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/gals.php';

		$app = JFactory::getApplication();
		$app->set('jcarousel', true);

		JHtml::stylesheet(JUri::base() . 'media/com_gals/fancyapps/source/jquery.fancybox.css');
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base() . "media/com_gals/js/jcarousel.min.js");
		JHtml::script(JUri::base() . "media/com_gals/fancyapps/source/jquery.fancybox.pack.js");
		JHtml::script(JUri::base() . "media/com_gals/js/gals-front.js");

		$params = $app->getParams();

		// Check for layout override
		$active = JFactory::getApplication()->getMenu()->getActive();

		if (isset($active->query['layout'])) {
			$this->setLayout($active->query['layout']);
		}

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
		$this->pagination = &$pagination;
		$this->params = &$params;
		$metaDesc = $this->params->get('menu-meta_description');
		if (!empty($metaDesc)) {
			$document = & JFactory::getDocument();
			$document->setDescription($metaDesc);
		}
		$this->title = $active->title;
		$this->items = $this->get('Items');
		$this->_setTitle();
		parent::display($tpl);
	}

	public function getPhotos($sCatalog) {
		$sCatalog = JFilterOutput::stringURLSafe($sCatalog);
		$aPhotos = array_slice(scandir("images/galleries/$sCatalog"), 2);
		return $aPhotos;
	}

	public function getFirstPhoto($sCatalog) {
		$aPhotos = $this->getPhotos($sCatalog);
		return $aPhotos[0];
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
