<?php

/**
 * Youtubes Module Entry Point
 * @license GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( dirname(__FILE__) . '/helper.php' );

$gals = new modGalsHelper();
$gal = $gals->getGals($params);
$alias = $gals->getAlias();
require( JModuleHelper::getLayoutPath('mod_gals', 'default'));