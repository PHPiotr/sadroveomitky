<?php
/*
 * @version 4.1.0
 * @package JotCache
 * @category Joomla 3.2
 * @copyright (C) 2010-2014 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('JPATH_PLATFORM') or die;
class JToolbarButtonSelector extends JToolbarButton {
protected $_name = 'Selector';
public function fetchButton($type = 'Selector', $name = '', $value = '', $link = '') {
$selected = array('', '', '');
if ($value > 2 || $value < 0) {
$value = 0;
}$selected[$value] = 'selected';
$htm = '<form action="' . $link . '" method="post" name="frontForm" id="frontForm"><label for="' . $name . '" class="element-invisible">Normal</label><select name="' . $name . '" id="' . $name . '" class="span12 small" onchange="this.form.submit()"><option value="0" ' . $selected[0] . '>Normal</option><option value="1" ' . $selected[1] . '>Mark</option><option value="2" ' . $selected[2] . '>Renew</option></select></form>';
return $htm;
}public function fetchId($type = 'Status', $name = '', $text = '', $task = '', $list = true, $hideMenu = false) {
return $this->_parent->getName() . '-' . $name;
}}