<?php
/*
 * @version 4.1.0
 * @package JotCache
 * @category Joomla 3.2
 * @copyright (C) 2010-2014 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.helper');
class RecacheRunner {
protected $dbo = null;
protected $recache_params = null;
public function __construct() {
    JLog::addLogger(array('text_file' => "jotcache.recache.log.php", 'text_entry_format' => "{DATE} {TIME}\t{MESSAGE}", 'text_file_path' => JPATH_ROOT . '/logs'), JLog::INFO, 'jotcache.recache');
$this->dbo = JFactory::getDBO();
$query = $this->dbo->getQuery(true);
$query->select('p.params')
->from('#__extensions AS p')
->where('p.enabled = 1')
->where('p.type = ' . $this->dbo->quote('plugin'))
->where('p.element = ' . $this->dbo->quote('recache'))
->where('p.folder = ' . $this->dbo->quote('jotcacheplugins'));
$this->dbo->setQuery($query);
$result = $this->dbo->loadResult();
$this->recache_params = JComponentHelper::getParams('com_jotcache');
}    public function doExecute($starturl, $jcplugin, $jcparams, $jcstates) {
    if (!defined('JPATH_PLUGINS') || !is_dir(JPATH_PLUGINS)) {
throw new Exception('JPATH_PLUGINS not defined');
}$logging = $this->recache_params->get('recachelog', 0) == 1 ? true : false;
if ($logging)
JLog::add('Starting recache run', JLog::INFO, 'jotcache.recache');
$query = $this->dbo->getQuery(true);
$query->select('p.*')
->from('#__extensions AS p')
->where('p.enabled = 1')
->where('p.type = ' . $this->dbo->quote('plugin'))
->where('p.folder = ' . $this->dbo->quote('jotcacheplugins'))
->order('p.ordering');
$this->dbo->setQuery($query);
$plugins = $this->dbo->loadObjectList();
if ($logging) {
JLog::add(sprintf('.loaded %d jotcache plugin(s)', count($plugins)), JLog::INFO, 'jotcache.recache');
}foreach ($plugins as $plugin) {
$className = 'plg' . ucfirst($plugin->folder) . ucfirst($plugin->element);
$element = preg_replace('#[^A-Z0-9-~_]#i', '', $plugin->element);
      if (!class_exists($className)) {
$path = sprintf(rtrim(JPATH_PLUGINS, '\\/') . '/jotcacheplugins/%s/%s.php', $element, $element);
if (is_file($path)) {
include $path;
if (!class_exists($className)) {
if ($logging) {
JLog::add(sprintf('..plugin class for `%s` not found in file', $element), JLog::INFO, 'jotcache.recache');
}continue;
}} else {
if ($logging) {
JLog::add(sprintf('..plugin file for `%s` not found', $element), JLog::INFO, 'jotcache.recache');
}continue;
}}if ($logging) {
JLog::add(sprintf('..registering `%s` plugin', $element), JLog::INFO, 'jotcache.recache');
}      $dispatcher = JDispatcher::getInstance();
$dispatcher->register('onJotcacheRecache', new $className(
$dispatcher,
array('params' => new JRegistry($plugin->params))
));}if (!isset($dispatcher)) {
$msg = "Warning : none of recache plugins enabled. Recache is stopped.\r\n";
JLog::add($msg, JLog::INFO, 'jotcache.recache');
exit($msg);
}    if ($logging) {
JLog::add('...triggering `onJotcacheRecache` event', JLog::INFO, 'jotcache.recache');
}foreach ($dispatcher->trigger('onJotcacheRecache',array($starturl, $jcplugin, $jcparams, $jcstates)) as $result) {
if ($logging) {
        if ($result[2] !== null) {
foreach ($result[2] as $key => $value) {
$browser_info = ($key === '') ? ' non-splitted browsers' : ' with browser:' . $key;
JLog::add(sprintf('....during recache %s returned %d hits', $browser_info, $value), JLog::INFO, 'jotcache.recache');
}}JLog::add(sprintf('...%s plugin returned `%s`', $result[0], $result[1]), JLog::INFO, 'jotcache.recache');
}}if ($logging) {
JLog::add("Finished recache run\r\n", JLog::INFO, 'jotcache.recache');
}}public function getDbo() {
return $this->dbo;
}function getData($url, $agent) {
$recache_params = JComponentHelper::getParams('com_jotcache');
$logging = $recache_params->get('recachelog', 0) == 1 ? true : false;
$ch = curl_init();
$timeout = 60;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$data = @curl_exec($ch);
if ($logging && curl_errno($ch)) {
JLog::add(sprintf('....for url %s returned %d error', $url, curl_error($ch)), JLog::INFO, 'jotcache.recache');
}curl_close($ch);
return $data;
}}?>
