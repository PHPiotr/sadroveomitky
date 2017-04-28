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
 * @return  array  A named array
 * @return  array
 */
function GalsBuildRoute(&$query) {
	$segments = array();

	if (isset($query['task'])) {
		unset($query['task']);
	}

	if (isset($query['view'])) {
		unset($query['view']);
	}

	if (isset($query['alias'])) {
		$segments[] = $query['alias'];
		unset($query['alias']);
	}

	return $segments;
}

function GalsParseRoute($segments) {
	
	$vars = array();
	$vars['view'] = 'gal';
	
	$count = count($segments);

	if ($count) {
		$count--;
		$segment = array_shift($segments);
		$vars['alias'] = $segment;
	}
	return $vars;
}
