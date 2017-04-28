<?php
defined('_JEXEC') or die('Restricted access');
?>
<a class="thumb" href="<?php echo JUri::base(true) . '/' . $alias . '/' . $gal['alias']; ?>" title="<?php echo $gal['title']; ?>">
	<img width="300" src="images/galleries/<?php echo $gal['alias'] . '/' . $gal['mainphoto']; ?>" alt="<?php echo $gal['title']; ?>" />
	<span class="video title"><?php echo $gal['title']; ?></span>
</a>

