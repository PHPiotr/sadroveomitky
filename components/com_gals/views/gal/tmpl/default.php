<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_gals
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (!empty($this->item)):
	?>
	<div class="gals<?php echo $this->pageclass_sfx; ?>"> 
		<div class="connected-carousels">
			<div class="stage" style="width: 800px">
				<h2 class="video gallery-title"><?php echo $this->item->title; ?></h2>
				<div class="carousel carousel-stage" style="height: 450px">
					<ul>
						<?php foreach ($this->photos as $item): ?>
							<li>
								<a class="fancybox" href="images/galleries/<?php echo $this->item->alias; ?>/<?php echo $item->photo; ?>" data-fancybox-group="group">
									<img class="main" src="images/galleries/<?php echo $this->item->alias; ?>/<?php echo $item->photo; ?>" alt="<?php echo $item->photo; ?>" width="800" />
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<a class="prev prev-stage" href="#"><span class="nav arrow-left"></span></a>
					<a class="next next-stage" href="#"><span class="nav arrow-right"></span></a>
				</div>			
			</div>
			<?php if (count($this->photos) > 1): ?>
				<div class="navigation" style="width:<?php echo (count($this->photos) === 2) ? '288px' : ((count($this->photos) === 3) ? '446px' : '606px'); ?>">
					<a href="#" class="prev prev-navigation"><span class="nav arrow-left"></span></a>
					<a href="#" class="next next-navigation"><span class="nav arrow-right"></span></a>
					<div class="carousel carousel-navigation" style="width:<?php echo (count($this->photos) === 2) ? '156px' : ((count($this->photos) === 3) ? '314px' : '472px'); ?>">
						<ul>
							<?php foreach ($this->photos as $item): ?>
								<li>
									<a href="images/galleries/<?php echo $this->item->alias; ?>/<?php echo $item->photo; ?>">
										<img class="main" src="images/galleries/<?php echo $this->item->alias; ?>/min/<?php echo $item->photo; ?>" alt="<?php echo $item->photo; ?>" width="156" />
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>	
			<?php endif; ?>
		</div>
	</div>
<?php endif;