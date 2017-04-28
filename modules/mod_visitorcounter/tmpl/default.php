<?php
defined('_JEXEC') or die('Restricted access');
?>
<ul class="nav nav-list pull-right">
	<li class="nav-header"><?php echo JText::_('VISITOR_COUNTER'); ?></li>
	<li><?php echo JText::_('TODAY') . ' <span>' . $today_visitors . '</span>'; ?></li>
	<li><?php echo JText::_('YESTERDAY') . ' <span>' . $yesterday_visitors . '</span>'; ?></li>
	<li><?php echo JText::_('WEEK') . ' <span>' . $week_visitors . '</span>'; ?></li>
	<li><?php echo JText::_('MONTH') . ' <span>' . $month_visitors . '</span>'; ?></li>
	<li><?php echo JText::_('ALL') . ' <span>' . $all_visitors . '</span>'; ?></li>
</ul>