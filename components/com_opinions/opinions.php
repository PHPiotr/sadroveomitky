<?php

defined('_JEXEC') or die;

$controller	= JControllerLegacy::getInstance('Opinions');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
