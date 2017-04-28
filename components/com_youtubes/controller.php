<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_youtubes
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Youtubes Controller
 *
 * @package     Joomla.Site
 * @subpackage  com_youtubes
 * @since       1.5
 */
class YoutubesController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		return parent::display($cachable, $urlparams);
	}
	
}
