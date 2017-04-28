<?php
/*
 * @version 4.1.0
 * @package JotCache
 * @category Joomla 3.2
 * @copyright (C) 2010-2014 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('JPATH_PLATFORM') or die;
final class BrowserAgents {
private static $defaultBrowser = 'Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00';
private static $browsers;
private static function _loadAgents() {
$lang = JFactory::getLanguage();
$lang->load('com_jotcache', JPATH_ADMINISTRATOR, null, false, true);
self::$browsers = array(
"chrome" => array('Chrome', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1284.0 Safari/537.13'),
"firefox" => array('Firefox', 'Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1'),
"msie" => array('IE', 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)'),
"msie6.0" => array('IE 6.0', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1)'),
"msie7.0" => array('IE 7.0', 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)'),
"msie8.0" => array('IE 8.0', 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 5.1; SLCC1; .NET CLR 1.1.4322)'),
"msie9.0" => array('IE 9.0', 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)'),
"safari" => array('Safari', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2'));
self::$browsers['tablet'] = array(JText::_('JOTCACHE_CLIENT_TABLET'), 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25');
self::$browsers['phone'] = array(JText::_('JOTCACHE_CLIENT_PHONE'), 'Mozilla/5.0 (Linux; U; Android 4.0.3; de-de; Galaxy S II Build/GRJ22) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30');
}public static function getDefaultAgent() {
return self::$defaultBrowser;
}public static function getBrowserAgents() {
if (!isset(self::$browsers)) {
self::_loadAgents();
}return self::$browsers;
}public static function getActiveBrowserAgents() {
if (!isset(self::$browsers)) {
self::_loadAgents();
}$clients = array();
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$query->select('params')
->from('#__extensions')
->where('type =' . $db->Quote('plugin'))
->where('folder =' . $db->Quote('system'))
->where('element =' . $db->Quote('jotcache'));
$cacheplg = $db->setQuery($query)->loadResult();
if ($cacheplg && stripos($cacheplg, 'cacheclient') !== false) {
$pars = json_decode($cacheplg);
$clients = $pars->cacheclient;
}$activeBrowsers = array();
$catchFirstNonsplitted = true;
$hasIeBrowser = preg_match('#"cacheclient":\[(.*msie\d+.*)\]#', $cacheplg);
foreach (self::$browsers as $key => $value) {
if (in_array($key, $clients)) {
$activeBrowsers[$key] = self::$browsers[$key];
} else {
if ($catchFirstNonsplitted) {
if (in_array('msie', $clients) && substr($key, 0, 4) == 'msie') {
continue;
}if ($hasIeBrowser > 0 && $key == 'msie') {
continue;
}$activeBrowsers[$key] = self::$browsers[$key];
$catchFirstNonsplitted = false;
}}}return $activeBrowsers;
}}