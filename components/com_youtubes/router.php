<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @return  array  A named array
 * @return  array
 */
function YoutubesBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['task']))
	{
		$segments[] = $query['task'];
		unset($query['task']);
	}
	if (isset($query['view']))
	{
		unset($query['view']);
	}
	if (isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	}

	return $segments;
}

/**
 * @return  array  A named array
 * @param   array
 *
 * Formats:
 *
 * index.php?/banners/task/id/Itemid
 *
 * index.php?/banners/id/Itemid
 */
function YoutubesParseRoute($segments)
{
	$vars = array();

	// view is always the first element of the array
	$count = count($segments);

	if ($count)
	{
		$count--;
		$segment = array_shift($segments);
		if (is_numeric($segment))
		{
			$vars['id'] = $segment;
		}
		else
		{
			$vars['task'] = $segment;
		}
	}

	if ($count)
	{
		$segment = array_shift($segments);
		if (is_numeric($segment))
		{
			$vars['id'] = $segment;
		}
	}

	return $vars;
}
