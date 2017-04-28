<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$oImages = json_decode($item->images);
$sImageFullText = $oImages->image_fulltext;
$item_heading = $params->get('item_heading', 'h4');

?>

<?php if (!empty($sImageFullText)): ?>
		<img class="reference-articles-news" src="<?php echo $pathPrefix . $sImageFullText; ?>" alt="<?php echo $item->title; ?>" title="<?php echo $item->title; ?>"
			 data-cycle-pager-template="<a href=#><?php echo $item->title; ?></a>"
			 data-cycle-title="<?php echo $item->title; ?>"
			 data-cycle-desc="<?php echo strip_tags($item->introtext); ?>"
			 data-cycle-link="<?php echo $item->link; ?>"
			 data-cycle-more="<?php echo $item->linkText; ?>">
<?php endif; ?>
