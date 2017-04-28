<?php
/*
 * @version 4.1.0
 * @package JotCache
 * @category Joomla 3.2
 * @copyright (C) 2010-2014 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
define('JOTCACHE_STATUS_NORMAL', ' #36C7AA, green');
define('JOTCACHE_STATUS_SPECIAL', ' #CBCB36, yellow');
define('JOTCACHE_STATUS_ATTENTION', ' #FAA528, orange');
define('JOTCACHE_STATUS_WARNING', ' #EE0000, red');
class MainModelMain extends JModelLegacy {
protected $_data = null;
protected $_db;
protected $_item = null;
protected $_pagination = null;
protected $_sql = "";
protected $_total = null;
public $exclude = array();
public $file_order = null;
public $file_order_Dir = null;
public $filter_actid = null;
public $filter_com = null;
public $filter_mark = null;
public $filter_view = null;
public $mode = null;
function ResetModelReset() {
parent::__construct();
}function getExclude() {
$query = $this->_db->getQuery(true);
$query->select('id,name,value')
->from('#__jotcache_exclude');
$rows = $this->_db->setQuery($query)->loadObjectList();
if (!empty($rows)) {
foreach ($rows as $row) {
$this->exclude[$row->name] = $row->value;
}}return $this->exclude;
}function getExcludePost($post) {
$name_list = "";
$cnt = 0;
foreach ($post as $key => $value) {
if (substr($key, 0, 3) == "ex_") {
if ($cnt > 0) {
$name_list.=",";
}$name_list.="'" . substr($key, 3) . "'";
$cnt++;
}}$query = $this->_db->getQuery(true);
$query->select('id,name,value')
->from('#__jotcache_exclude')
->where('name IN (' . $name_list . ')');
$rows = $this->_db->setQuery($query)->loadObjectList();
if (!empty($rows)) {
foreach ($rows as $row) {
$this->exclude[$row->name] = $row->value;
}}return $this->exclude;
}function getData() {
$app = JFactory::getApplication();
$data = new stdclass;
$params = JComponentHelper::getParams("com_jotcache");
$this->mode = (bool) $params->get('mode');
$where = array();
$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
$limitstart = $app->getUserStateFromRequest('com_jotcache.limitstart', 'limitstart', 0, 'int');
$search = $app->getUserStateFromRequest('com_jotcache.search', 'filter_search', '', 'string');
$search = JString::strtolower($search);
$this->filter_com = $app->getUserStateFromRequest('com_jotcache.filter_com', 'filter_com', '', 'cmd');
$this->filter_view = $app->getUserStateFromRequest('com_jotcache.filter_view', 'filter_view', '', 'cmd');
$this->filter_mark = $app->getUserStateFromRequest('com_jotcache.filter_mark', 'filter_mark', '', 'cmd');
$query = $this->_db->getQuery(true);
if ($this->filter_com) {
$query->where("m.com='$this->filter_com'");
}if ($this->filter_view) {
$part = $this->_db->quoteName('view');
$query->where("m.$part='$this->filter_view'");
}if (strlen($this->filter_mark) > 0) {
$query->where("m.mark='$this->filter_mark'");
}if (count($search) > 0) {
if ($this->mode) {
$where[] = 'LOWER(m.uri) LIKE ' . $this->_db->quote('%' . $this->_db->escape($search, true) . '%', false);
} else {
$query->where('LOWER(m.title) LIKE ' . $this->_db->quote('%' . $this->_db->escape($search, true) . '%', false));
}}    $query->select('COUNT(*)')
->from('#__jotcache AS m ')
->where($where);
$total = $this->_db->setQuery($query)->loadResult();
jimport('joomla.html.pagination');
$data->pageNav = new JPagination($total, $limitstart, $limit);
if ($this->mode) {
$this->file_order = $app->getUserStateFromRequest('com_jotcache.file_order', 'filter_order', 'm.uri', 'cmd');
} else {
$this->file_order = $app->getUserStateFromRequest('com_jotcache.file_order', 'filter_order', 'm.title', 'cmd');
}$this->file_order_Dir = $app->getUserStateFromRequest('com_jotcache.file_order_Dir', 'filter_order_Dir', 'asc', 'word');
$query->clear('select');
$query->select('m.*');
if ($this->file_order != "") {
$query->order("$this->file_order $this->file_order_Dir");
}$data->rows = $this->_db->setQuery($query, $data->pageNav->limitstart, $data->pageNav->limit)->loadObjectList();
if ($data->rows === null) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
}$this->checkExpired($data->rows);
$data->mode = $this->mode;
$data->showcookies = (bool) $params->get('showcookies');
$data->showfname = (bool) $params->get('showfname');
$data->fastdelete = (bool) $params->get('fastdelete');
    return $data;
}function checkExpired(&$data) {
$root = JPATH_SITE . '/cache/page';
for ($i = 0; $i < count($data); $i++) {
$filename = $root . '/' . $data[$i]->fname . '.php_expire';
if ($this->_db->name == "sqlsrv") {
$data[$i]->ftime = substr($data[$i]->ftime, 0, 19);
}if (file_exists($filename)) {
$exp = file_get_contents($filename);
if (time() - $exp > 0)
$data[$i]->ftime = '(' . $data[$i]->ftime . ')';
} else {
$data[$i]->ftime = '#' . $data[$i]->ftime . '#';
}}}function getBcData() {
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('*')
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . ' = 2')
->order($this->_db->quoteName('value'));
return $this->_db->setQuery($this->_sql, 0, 20)->loadObjectList();
}function getLists() {
$where = array();
$lists = array();
$query = $this->_db->getQuery(true);
$query->select('com as value, com as text')
->from('#__jotcache AS c')
->group('com')
->order('com');
$lists['com'] = JHtml::_('select.options', $this->_db->setQuery($query)->loadObjectList(), 'value', 'text', $this->filter_com);
if ($this->filter_com) {
$where[] = "c.com='$this->filter_com'";
}$part = $this->_db->quoteName('view');
$where[] = "c.$part<>''";
$query->clear();
$query->select($this->_db->quoteName('view', 'value'))
->select($this->_db->quoteName('view', 'text'))
->from('#__jotcache AS c')
->where($where)
->group($this->_db->quoteName('view'))
->order($this->_db->quoteName('view'));
$lists['view'] = JHtml::_('select.options', $this->_db->setQuery($query)->loadObjectList(), 'value', 'text', $this->filter_view);
$mark[] = JHTML::_('select.option', '0', JText::_('JOTCACHE_RS_SEL_MARK_NO'), 'value', 'text');
$mark[] = JHTML::_('select.option', '1', JText::_('JOTCACHE_RS_SEL_MARK_YES'), 'value', 'text');
$lists['mark'] = JHtml::_('select.options', $mark, 'value', 'text', $this->filter_mark);
$query->clear();
$query->select('name')
->from('#__extensions')
->where("type = 'plugin'")
->where("folder = 'system'")
->where("enabled = '1'")
->order('ordering DESC');
$lists['last'] = ($this->_db->setQuery($query, 0, 1)->loadResult() != "JotCache") ? true : false;
if ($lists['last']) {
$query->clear('where');
$query->where("type = 'plugin'")
->where("folder = 'system'");
$lists['last'] = ($this->_db->setQuery($query, 0, 1)->loadResult() != "JotCache") ? true : false;
}$query->clear();
$query->select('extension_id')
->from('#__extensions')
->where("type = 'plugin'")
->where("folder = 'system'")
->where("name = 'JotCache'");
$lists['plgid'] = $this->_db->setQuery($query)->loadResult();
return $lists;
}function getStatus() {
$status = array();
$app = JFactory::getApplication();
$caching = (int) $app->getCfg('caching');
switch ($caching) {
case 0:
$status['gclass'] = JOTCACHE_STATUS_SPECIAL;
$status['gtitle'] = JText::_('JOTCACHE_RS_GLOBAL_SPECIAL');
break;
case 1:
$status['gclass'] = JOTCACHE_STATUS_NORMAL;
$status['gtitle'] = JText::_('JOTCACHE_RS_GLOBAL_NORMAL');
break;
default:
$status['gclass'] = JOTCACHE_STATUS_WARNING;
$status['gtitle'] = JText::_('JOTCACHE_RS_GLOBAL_WARNING');
break;
}$root = JPATH_SITE . '/cache/page/';
$cnt = 0;
if (file_exists($root) && $handle = opendir($root)) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") {
$ext = strrchr($file, ".");
          if ($ext == ".php_expire") {
$time = @file_get_contents($root . $file);
if ($time >= time()) {
$cnt++;
}}}}closedir($handle);
}$status['clear'] = $cnt;
return $status;
}function getTplLists() {
$lists = array();
$sel_id = 1;
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('position')
->from('#__modules')
->where('client_id = 0')
->where('published = 1')
->where('position <>' . $this->_db->quote(''))
->group('position')
->order('position');
$this->_db->setQuery($this->_sql);
$tquery = $this->_db->getQuery();
$out = $tquery->__toString();
$items = $this->_db->loadColumn();
natcasesort($items);
$lists['pos'] = $items;
$this->_sql->clear();
$this->_sql->select($this->_db->quoteName('value'))
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . ' = 4')
->where($this->_db->quoteName('name') . ' = ' . (int) $sel_id);
$value = $this->_db->setQuery($this->_sql)->loadResult();
$tplDef = unserialize($value);
$lists['value'] = is_array($tplDef) ? $tplDef : array();
return $lists;
}function refresh() {
$app = JFactory::getApplication();
$app->setUserState('com_jotcache.filter_com', '');
$app->setUserState('com_jotcache.filter_view', '');
$app->setUserState('com_jotcache.filter_mark', '');
$root = JPATH_SITE . '/cache/page';
$query = $this->_db->getQuery(true);
if (!file_exists($root)) {
$query->delete()->from('#__jotcache');
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
}return;
}$query->clear();
$query->select('fname')->from('#__jotcache');
$rows = $this->_db->setQuery($query)->loadObjectList();
$deleted = array();
$existed = array();
    foreach ($rows as $row) {
$filename = $root . '/' . $row->fname . '.php_expire';
if (file_exists($filename)) {
$exp = file_get_contents($filename);
if (time() - $exp > 0) {
$deleted[] = $row->fname;
} else {
$existed[$row->fname] = 1;
}} else {
$deleted[] = $row->fname;
}}    if (count($deleted) > 0) {
$list = implode("','", $deleted);
$query->clear();
$query->delete()
->from('#__jotcache')
->where("fname IN ('$list')");
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
}}    if ($handle = opendir($root)) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") {
$ext = strrchr($file, ".");
$fname = substr($file, 0, -strlen($ext));
if (!array_key_exists($fname, $existed) && ($ext == ".php" || $ext == ".php_expire")) {
unlink($root . '/' . $file);
}}}closedir($handle);
}}function renew($token) {
$root = JPATH_SITE . '/cache/page/';
if (file_exists($root . $token . '.php')) {
unlink($root . $token . '.php');
unlink($root . $token . '.php_expire');
}}function resetMark() {
$query = $this->_db->getQuery(true);
$query->update($this->_db->quoteName('#__jotcache'))->set('mark=NULL');
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
}}function delete() {
$app = JFactory::getApplication();
$root = JPATH_SITE . '/cache/page';
$cid = $app->input->get('cid', array(0), 'array');
$list = implode("','", $cid);
$query = $this->_db->getQuery(true);
$query->delete()
->from('#__jotcache')
->where("fname IN ('$list')");
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
}foreach ($cid as $fname) {
if (file_exists($root . '/' . $fname . '.php')) {
unlink($root . '/' . $fname . '.php');
unlink($root . '/' . $fname . '.php_expire');
}}}function deleteall() {
$query = $this->_db->getQuery(true);
$query->delete()
->from('#__jotcache');
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
}$this->refresh();
}function getExList() {
$app = JFactory::getApplication();
$data = new stdclass;
$limit = $app->getUserStateFromRequest('com_jotcache.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
$limitstart = $app->getUserStateFromRequest('com_jotcache.limitstart', 'limitstart', 0, 'int');
$data->exclude = $this->getExclude();
$query = $this->_db->getQuery(true);
$query->select('COUNT(*)')
->from('#__extensions')
->where("type='component'")
->where("element<>''")
->order("element");
$total = $this->_db->setQuery($query)->loadResult();
$query->clear('select')
->select(array("extension_id AS " . $this->_db->quoteName('id'), $this->_db->quoteName('name'), "element AS " . $this->_db->quoteName('option')))
->order('name');
$data->rows = $this->_db->setQuery($query)->loadObjectList();
if ($data->rows === null) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
}return $data;
}function store($post, $cid) {
$query = $this->_db->getQuery(true);
if (count($cid) > 0) {
$idlist = implode(',', $cid);
$query->select($this->_db->quoteName('element', 'option'))
->from('#__extensions')
->where("extension_id IN ($idlist)");
$rows = $this->_db->setQuery($query)->loadObjectList();
      $exclude_jc = array();
foreach ($rows as $row) {
$views = 'ex_' . $row->option;
$value = array_key_exists($views, $post) ? $post[$views] : '';
if ($value == '')
$value = '1';
$exclude_jc[$row->option] = $value;
}$exclude_db = $this->getExcludePost($post);
$upd = $exclude_jc;
$del = array_diff_key($exclude_db, $exclude_jc);
$ins = array_diff_key($exclude_jc, $exclude_db);
if (count($del) > 0) {
$del_list = implode("','", array_keys($del));
$query->clear();
$query->delete()
->from($this->_db->quoteName('#__jotcache_exclude'))
->where("name IN ('$del_list')");
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}}foreach ($ins as $option => $views) {
$query->clear();
$query->insert('#__jotcache_exclude')
->columns('name,value,type')
->values("'$option', '$views','0'");
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}}foreach ($upd as $option => $views) {
$upd_list = implode("','", array_keys($upd));
$root = JPATH_SITE . '/cache/page';
$query->clear();
$query->select($this->_db->Quote('fname'))
->from('#__jotcache')
->where("com IN ('$upd_list')");
$rows = $this->_db->setQuery($query)->loadObjectList();
foreach ($names as $name) {
if (file_exists($root . '/' . $name->fname . '.php')) {
unlink($root . '/' . $name->fname . '.php');
unlink($root . '/' . $name->fname . '.php_expire');
}}$query->clear();
$query->update($this->_db->quoteName('#__jotcache_exclude'))
->set($this->_db->quoteName('value') . "='$views'")
->where($this->_db->quoteName('name') . " = '$option'");
if (!$this->_db->setQuery($query)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}}}return true;
}function tplstore($post, $cids) {
$tpl_id = 1;
if (count($cids) > 0 && $tpl_id > 0) {
$tplexDef = array();
foreach ($cids as $cid) {
$style = array_key_exists('ex1_' . $cid, $post) ? trim($post['ex1_' . $cid]) : '';
$attr = array_key_exists('ex2_' . $cid, $post) ? trim($post['ex2_' . $cid]) : '';
$tplexDef[$cid] = $style . '|' . $attr;
}$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('*')
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . ' = 4')
->where($this->_db->quoteName('name') . ' = ' . (int) $tpl_id);
$tpl_stored = $this->_getListCount($this->_sql);
$packed = serialize($tplexDef);
$this->_sql->clear();
if ($tpl_stored == 1) {
$this->_sql->update($this->_db->quoteName('#__jotcache_exclude'))
->set($this->_db->quoteName('value') . ' = ' . $this->_db->quote($packed))
->where($this->_db->quoteName('type') . ' = 4')
->where($this->_db->quoteName('name') . ' = ' . (int) $tpl_id);
if (!$this->_db->setQuery($this->_sql)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}} else {
$this->_sql->insert('#__jotcache_exclude')
->columns('name,value,type')
->values("'$tpl_id','$packed','4'");
if (!$this->_db->setQuery($this->_sql)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}}}return $tpl_id;
}function bcstore($post) {
$upd = array();
$ins = array();
$params = JComponentHelper::getParams('com_jotcache');
$defbtime = (int) $params->get('defbtime');
foreach ($post as $key => $value) {
$part = substr($key, 0, 2);
switch ($part) {
case 'ux':
$id = (int) substr($key, 2);
$value = $this->urifilter($value);
if ($value === false)
break;
$upd[] = array($id, $value, (int) $post['uy' . $id]);
break;
case 'ix':
if ($value != "") {
$id = (int) substr($key, 2);
$value = $this->urifilter($value);
if ($value === false)
break;
if ($post['iy' . $id] == 0) {
$post['iy' . $id] = $defbtime;
}$ins[] = array($value, (int) $post['iy' . $id]);
}break;
default:
break;
}}$set = array();
$this->_sql = $this->_db->getQuery(true);
foreach ($upd as $item) {
$this->_sql->clear();
$this->_sql->update($this->_db->quoteName('#__jotcache_exclude'))
->set($this->_db->quoteName('name') . ' = ' . (int) $item[2])
->set($this->_db->quoteName('value') . ' = ' . $this->_db->quote($item[1]))
->where($this->_db->quoteName('type') . ' = 2')
->where($this->_db->quoteName('id') . ' = ' . (int) $item[0]);
if (!$this->_db->setQuery($this->_sql)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}$set[] = $item[1];
}foreach ($ins as $item) {
if (!in_array($item[0], $set)) {
$this->_sql->clear();
$name = $item[1];
$value = $item[0];
$this->_sql->insert('#__jotcache_exclude')
->columns('name,value,type')
->values("'$name', '$value', '2'");
if (!$this->_db->setQuery($this->_sql)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}} else {
JError::raiseNotice(100, JText::_('JOTCACHE_BCACHE_NEW_URI') . ' ' . $item[0] . ' ' . JText::_('JOTCACHE_BCACHE_DUPL'));
}$set[] = $item[0];
}return $this->bcpack();
}function urifilter($value) {
if ($value == "")
return false;
if (substr($value, 0, 1) != "/")
$value = "/" . $value;
$pattern = '#^[\/]([a-zA-Z0-9-_:\.]*[\/]?)*[?]?([a-zA-Z0-9-_:\.]*[=]?[a-zA-Z0-9-_:\.]*[&]?)*#';
preg_match($pattern, (string) $value, $matches);
if (is_array($matches)) {
$filtered = @ (string) $matches[0];
if ($value == $filtered)
return $value;
}JError::raiseNotice(100, JText::_('JOTCACHE_BCACHE_NEW_URI') . ' ' . $value . ' ' . JText::_('JOTCACHE_BCACHE_BLOCKED'));
return false;
}function bcdelete() {
$input = JFactory::getApplication()->input;
$cid = $input->post->get('cid', array(0), 'array');
JArrayHelper::toInteger($cid, array(0));
$list = implode("','", $cid);
$this->_sql = $this->_db->getQuery(true);
$id = $this->_db->quoteName('id');
$this->_sql->delete('#__jotcache_exclude')
->where("$id IN ('$list')");
if (!$this->_db->setQuery($this->_sql)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}return $this->bcpack();
}function bcpack() {
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('*')
->from('#__jotcache_exclude')
->where($this->_db->quoteName('type') . ' = 2')
->order('value');
$rows = $this->_db->setQuery($this->_sql)->loadObjectList();
$bcDef = array();
foreach ($rows as $row) {
$bcDef[$row->value] = $row->name;
}$packed = serialize($bcDef);
$this->_sql->clear();
$this->_sql->select('COUNT(*)')
->from('#__jotcache_exclude')
->where("type='3'");
$bcDefExists = (bool) $this->_db->setQuery($this->_sql)->loadResult();
$this->_sql->clear();
if ($bcDefExists) {
$this->_sql->update($this->_db->quoteName('#__jotcache_exclude'))
->set($this->_db->quoteName('name') . ' = ' . $this->_db->quote('pack'))
->set($this->_db->quoteName('value') . ' = ' . $this->_db->quote($packed))
->where("type='3'");
if (!$this->_db->setQuery($this->_sql)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}} else {
$this->_sql->insert('#__jotcache_exclude')
->columns('name,value,type')
->values("'pack', '$packed', '3'");
if (!$this->_db->setQuery($this->_sql)->query()) {
JError::raiseNotice(100, $this->_db->getErrorMsg());
return false;
}}return true;
}function getCachedContent() {
$page_data = new stdclass;
$input = JFactory::getApplication()->input;
$fname = $input->getCmd('fname');
$this->_sql = $this->_db->getQuery(true);
$this->_sql->select('title')
->from('#__jotcache')
->where($this->_db->quoteName('fname') . ' = ' . $this->_db->quote($fname));
$page_data->title = $this->_db->setQuery($this->_sql)->loadResult();
$dir_path = JPATH_ROOT . '/cache/page/';
$fname_path = $dir_path . $fname . '.php';
$content = @file_get_contents($fname_path);
$page_data->content = trim(str_replace('<?php die("Access Denied"); ?>', '', $content));
    $page_data->error = false;
preg_match_all('#<jot\s([_a-zA-Z0-9-]*)\s[es]\s((?:\w*="[_a-zA-Z0-9-\.]*"\s*)*)>#', $page_data->content, $matches);
$marks = $matches[0];
$checks = array_unique($matches[1]);
$attrs = $matches[2];
$err = array();
$cnt = 0;
for ($i = 0; $i < count($marks); $i = $i + 2) {
if ($marks[$i] != "<jot " . @$checks[$i] . " s " . @$attrs[$i] . ">" || @$marks[$i + 1] != "<jot " . @$checks[$i] . " e >") {
$err[] = @$checks[$i];
} else {
$cnt++;
}}if (array_key_exists(0, $err))
$page_data->error = true;
    $page_data->count = $cnt;
return $page_data;
}function transferFile($file_path) {
$fsize = filesize($file_path);
    $mtype = "application/force-download";
    $asfname = basename($file_path);
ob_end_clean();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$asfname\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);
@readfile($file_path);
exit;
}}