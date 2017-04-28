<?php

defined('_JEXEC') or die;

class OpinionsViewOpinions extends JViewLegacy {

	public function display($tpl = null) {

		$app = JFactory::getApplication();
		$params = $app->getParams();

		JHtml::_('jquery.framework');
		JHtml::script('media/com_opinions/js/opinions.js');
		$this->form = $this->get('Form');
		$this->script = $this->get('Script');
		$this->opinions = $this->get('Opinions');
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
