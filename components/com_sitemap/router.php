<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @param   array
 * @return  array
 */
function sitemapBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['view']))
	{
		unset($query['view']);
	}
	return $segments;
	
	if (isset($query['Itemid']))
	{
		unset($query['Itemid']);
	}
	return $segments;
}

/**
 * @param   array
 * @return  array
 */
function sitemapParseRoute($segments)
{
	$vars = array();

	$searchword	= array_shift($segments);
	$vars['view'] = 'sitemap';

	return $vars;
}
