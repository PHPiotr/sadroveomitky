<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_email
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * HTML View class for the email component
 *
 * @package     Joomla.Site
 * @subpackage  com_email
 * @since       1.0
 */
class EmailViewEmail extends JViewLegacy {

	public function display($tpl = null) {

		$app = JFactory::getApplication();
		$params = $app->getParams();

		JHtml::_('jquery.framework');
		JHtml::script('media/com_email/js/email.js');
		$this->form = $this->get('Form');
		$this->script = $this->get('Script');
		$this->session = &JFactory::getSession();
		$this->params = &$params;
		$metaDesc = $this->params->get('menu-meta_description');
		if (!empty($metaDesc)) {
			$document = & JFactory::getDocument();
			$document->setDescription($metaDesc);
		}
		
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
