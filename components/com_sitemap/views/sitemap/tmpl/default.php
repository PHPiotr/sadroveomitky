<?php

defined('_JEXEC') or die;
$result = $this->items;

echo '<ul class="nav nav-list pull-left well">';
echo '<li class="nav-header">' . $this->params->get('page_title', '') . '</li>';
foreach ($result as $val) {
	if ($val->parent_id == 1) {
		echo '<li>';
		if ($val->path == 'uvod' || $val->path == 'start') {
			echo JHtml::link(JUri::base() . $this->lang, $val->title, array('title' => $val->title));
		} else {
			echo JHtml::link(JUri::base() . $this->lang . $val->path, $val->title, array('title' => $val->title));
		}
		if (((int) $val->rgt - (int) $val->lft === 1) && $val->level == 1) {
			echo '</li>';
		} else {
			echo '<ul class="nav nav-list">';
			foreach ($result as $v) {
				if ($val->id === $v->parent_id) {
					echo '<li>';
					echo JHtml::link(JUri::base() . $this->lang . $v->path, $v->title, array('title' => $v->title));
					echo '</li>';
				}
			}
			echo '</ul>';
			echo '</li>';
		}
	}
}
echo '</ul>';