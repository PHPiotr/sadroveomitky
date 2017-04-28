<?php

/**
 * Youtubes Module Entry Point
 * @license GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( dirname(__FILE__) . '/helper.php' );

$oYoutube = new modYoutubesHelper();
$sYoutube = $oYoutube->getYoutube($params);
require( JModuleHelper::getLayoutPath('mod_youtubes', 'default'));