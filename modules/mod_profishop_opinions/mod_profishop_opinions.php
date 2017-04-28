<?php

defined('_JEXEC') or die('Restricted access');

$sModuleclassSfx = htmlspecialchars($params->get('moduleclass_sfx',''));

// Get a db connection.
$db = JFactory::getDbo();

// Create a new query object.
$query = $db->getQuery(true);

// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
$query->select($db->quoteName(array('name', 'city', 'opinion', 'created_at')));
$query->from($db->quoteName('#__opinions'));
$query->where($db->quoteName('active') . ' = 1');
$query->order('opinion_id DESC');

// Reset the query using our newly populated query object.
$db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
$opinions = $db->loadObjectList();

require JModuleHelper::getLayoutPath('mod_profishop_opinions', $params->get('layout', 'default'));
