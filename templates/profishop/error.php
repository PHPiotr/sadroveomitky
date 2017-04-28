<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params; 
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if ($task == "edit" || $layout == "form") {
	$fullWidth = 1;
} else {
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add current user information
$user = JFactory::getUser();

// Logo file or site title param
if ($params->get('logoFile')) {
	$logo = '<img src="' . JUri::root() . $params->get('logoFile') . '" alt="' . $sitename . '" title="' . $sitename . '" />';
} elseif ($params->get('sitetitle')) {
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($params->get('sitetitle')) . '</span>';
} else {
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo $this->title; ?> <?php echo htmlspecialchars($this->error->getMessage()); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<?php
// Use of Google Font
			if ($params->get('googleFont')) {
				?>
				<link href='//fonts.googleapis.com/css?family=<?php echo $params->get('googleFontName'); ?>' rel='stylesheet' type='text/css' />
				<style type="text/css">
					h1,h2,h3,h4,h5,h6,.site-title{
						font-family: '<?php echo str_replace('+', ' ', $params->get('googleFontName')); ?>', sans-serif;
					}
				</style>
				<?php
			}
			?>
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />

			<?php
			$debug = JFactory::getConfig()->get('debug_lang');
			if ((defined('JDEBUG') && JDEBUG) || $debug) {
				?>
				<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css" />
				<?php
			}
			?>
			<?php
// If Right-to-Left
			if ($this->direction == 'rtl') {
				?>
				<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-rtl.css" type="text/css" />
				<?php
			}
			?>
			<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
			<?php
			// Template color
			if ($params->get('templateColor')) {
				?>
				<style type="text/css">
					body.site
					{
						border-top: 1px solid rgb(170, 188, 207);
						padding: 0px;
						background-color: <?php echo $params->get('templateBackgroundColor'); ?>
					}
					a
					{
						color: <?php echo $params->get('templateColor'); ?>;
					}

				</style>
				<!--[if gte IE 9]>
			<style type="text/css">
			.gradient {
			filter: none;
			}
			</style>
			<![endif]-->
				<?php
			}
			?>
			<!--[if lt IE 9]>
				<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
			<![endif]-->
	</head>

	<body class="site <?php
	echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
	?>">

		<!-- Body -->
		<div class="body error">
			<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
				<!-- Header -->
				<div class="header">
					<div class="header-inner clearfix">
						<a class="brand pull-left" href="<?php echo $this->baseurl; ?>/" title="<?php echo $sitename; ?>" rel="nofollow">
							<?php
							echo $logo;
							$aSitename = explode(' ', $sitename);
							?> <?php if ($params->get('sitedescription')): ?>
								<h1 class="site-description light">
									<span class="dark"><?php echo $aSitename[0]; ?></span>
									<?php echo $aSitename[1] . ' ' . $aSitename[2]; ?>
								</h1>
							<?php endif; ?>
						</a>
					</div>
				</div>
				<div class="row-fluid">
					<div id="content" class="span12 item-page" style="padding-right: 20px;">
						<!-- Begin Content -->
						<div class="page-header">
							<h1 style="color:rgb(124, 126, 184);text-align: center;"><?php echo JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
						</div>
						<p><strong><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
						<p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
						
						<ul>
							<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
							<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
							<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
							<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
						</ul>

							<?php if (JModuleHelper::getModule('search')) : ?>
								<p><strong><?php echo JText::_('JERROR_LAYOUT_SEARCH'); ?></strong></p>
								<p><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
								<?php echo $doc->getBuffer('module', 'search'); ?>
							<?php endif; ?>
							<p><?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
							<p><a href="<?php echo $this->baseurl; ?>/" class="custom-btn btn"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?> <span class="icon-home"></span></a></p>

						<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
						<blockquote>
							<span class="label label-inverse"><?php echo $this->error->getCode(); ?></span> <?php echo $this->error->getMessage(); ?>
						</blockquote>

					</div>
				</div>
			</div>
		</div>
		<!-- Footer -->
		<div class="footer">
			<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
				<hr />
				<?php echo $doc->getBuffer('modules', 'footer', array('style' => 'none')); ?>

			</div>
		</div>
		<?php echo $doc->getBuffer('modules', 'debug', array('style' => 'none')); ?>
	</body>
</html>
