<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_gals
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (!empty($this->items)):
	?>
	<div class="gals<?php echo $this->pageclass_sfx; ?>">
		<div class="connected-carousels">
			<div class="stage" style="width: 800px">
				<h2 class="video gallery-title"><?php echo $this->title; ?></h2>
				<div class="carousel carousel-stage" style="height: 450px">
					<ul>
						<?php foreach ($this->items as $item): ?>
							<?php $sMainPhoto = (!empty($item->mainphoto)) ? $item->mainphoto : $this->getFirstPhoto($item->title); ?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_gals&view=gal&alias=' . $item->alias); ?>" title="<?php echo $item->title; ?>">
									<img src="images/galleries/<?php echo $item->alias; ?>/<?php echo $sMainPhoto; ?>" alt="<?php echo $item->title; ?>" title="<?php echo $item->title; ?>" width="800">
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php if (count($this->items) > 1): ?>
						<a class="prev prev-stage" href="#"><span class="nav arrow-left" id="l"></span></a>
						<a class="next next-stage" href="#"><span class="nav arrow-right" id="r"></span></a>
					<?php endif; ?>
				</div>			
			</div>
			<?php if (count($this->items) > 1): ?>
				<div class="navigation" style="width:<?php echo (count($this->items) === 2) ? '288px' : ((count($this->items) === 3) ? '446px' : '606px'); ?>">

					<a href="#" class="prev prev-navigation visible-desktop"><span class="nav arrow-left"></span></a>
					<a href="#" class="next next-navigation visible-desktop"><span class="nav arrow-right"></span></a>


					<div class="carousel carousel-navigation" style="width:<?php echo (count($this->items) === 2) ? '156px' : ((count($this->items) === 3) ? '314px' : '472px'); ?>">
						<ul>
							<?php foreach ($this->items as $item): ?>
								<?php $sMainPhoto = (!empty($item->mainphoto)) ? $item->mainphoto : $this->getFirstPhoto($item->title); ?>
								<li>
									<a class="thumb" href="<?php echo JRoute::_('index.php?option=com_gals&view=gal&alias=' . $item->alias); ?>" title="<?php echo $item->title; ?>">
										<img src="images/galleries/<?php echo $item->alias; ?>/min/<?php echo $sMainPhoto; ?>" alt="<?php echo $item->title; ?>" title="<?php echo $item->title; ?>" width="156">
										<span class="video title"><?php echo JHTML::_('string.truncate', ($item->title), 25); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>

</div>