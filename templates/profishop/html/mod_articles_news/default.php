<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::script('jquery.framework');
JHtml::script(JUri::base() . 'templates/profishop/js/cycle2.js');
$lang = JFactory::getLanguage()->getTag();
$pathPrefix = $lang == 'pl-PL' ? '../' : '';

        // Get a db connection.
$db = JFactory::getDbo();

// Create a new query object.
$query = $db->getQuery(true);

// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
$query->select($db->quoteName(array('name', 'city', 'opinion', 'created_at')));
$query->from($db->quoteName('#__opinions'));
$query->setLimit('1');
$query->where($db->quoteName('active') . ' = 1');
$query->order('opinion_id DESC');

// Reset the query using our newly populated query object.
$db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
$opinion = $db->loadObject();

if (!empty($opinion)) {

    $opinion->title = $opinion->name . ', ' . $opinion->city;
    $opinion->introtext = $opinion->opinion;

    if ($lang == 'pl-PL') {
        $opinion->link = '/pl/referencje';
        $opinion->linkText = 'Zobacz wszystkie referencje...';
    } else {
        $opinion->link = '/reference';
        $opinion->linkText = 'Zobrazit všechny reference...';
    }
    $opinion->images = json_encode(array('image_fulltext' => 'images/strojni-omitky.jpg'));
    $opinion->fulltext = $opinion->opinion;

    array_push($list, $opinion);
}

?>
<div class="cycle-slideshow newsflash<?php echo $moduleclass_sfx; ?>"
	 data-cycle-fx=fade
	 data-cycle-timeout=5000
	 data-cycle-pager="#per-slide-template"
	 data-cycle-pause-on-hover="true"
	 data-cycle-overlay-template="<h2>{{title}}</h2><div class='desc'>{{desc}}</div><a class='btn custom-btn' href='{{link}}' title='{{title}}'>{{more}} <span class='icon-arrow-right'></span></a>"
	 >
	<div class="cycle-overlay"></div>

	<?php



	foreach ($list as $item) :
        	if ($item->alias === 'chytre-zatepleni') {
            		$item->link = 'http://chytrezateplenidiamant.cz';
        	}
        	if ($item->alias === 'ocieplanie-pianka') {
            		$item->link = 'https://ocieplaniepiankadiamant.pl';
        	}
		require JModuleHelper::getLayoutPath('mod_articles_news', '_item');
	endforeach;
	?>
</div>

<!-- empty element for pager links -->
<div id="per-slide-template" class="cycle2-pager"></div>
