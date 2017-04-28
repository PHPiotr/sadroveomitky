<?php

defined('_JEXEC') or die;

$controller	= JControllerLegacy::getInstance('Sitemap');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();