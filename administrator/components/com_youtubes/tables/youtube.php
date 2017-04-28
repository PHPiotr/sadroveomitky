<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * youtube table
 *
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 * @since       1.5
 */
class YoutubesTableYoutube extends JTable {

	/**
	 * Constructor
	 *
	 * @since   1.5
	 */
	public function __construct(&$_db) {
		parent::__construct('#__youtubes', 'id', $_db);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean
	 * @see     JTable::check
	 * @since   1.5
	 */
	public function check() {
		// Set title
		$this->title = htmlspecialchars_decode($this->title, ENT_QUOTES);
 
		// Set link
		$this->link = $this->_checkLink($this->link);
		if(!$this->link) {
			$this->setError(JText::_('COM_YOUTUBES_WRONG_LINK'));
			return false;
		}

		// Set ordering
		if ($this->state < 0) {
			// Set ordering to 0 if state is archived or trashed
			$this->ordering = 0;
		} elseif (empty($this->ordering)) {
			// Set ordering to last if ordering was 0
			$this->ordering = self::getNextOrder('state>=0');
		}

		return true;
	}

	/**
	 * Check if link is valid.
	 * 
	 * @param type $sLink	Link of video from youtube.
	 * @return mixed		Video ID on success / False if not matched.
	 */
	protected function _checkLink($sLink) {
		
		$sRegExp = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
		
		preg_match($sRegExp, $sLink, $sMatch);
		if (($sMatch && strlen($sMatch[2]) == 11)) {
			return $sMatch[2];
		}
		
		preg_match($sRegExp, 'http://www.youtube.com/watch?v=' . $sLink, $sMatch);
		if (($sMatch && strlen($sMatch[2]) == 11)) {
			return $sMatch[2];
		}
		
		return false;
	}

	/**
	 * Overloaded bind function
	 *
	 * @param   array  $hash named array
	 * @return  null|string	null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = array()) {
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['params']);

			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed	An optional array of primary key values to update.  If not
	 * 					set the instance property value is used.
	 * @param   integer The publishing state. eg. [0 = unpublished, 1 = published, 2=archived, -2=trashed]
	 * @param   integer The user id of the user performing the operation.
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function publish($pks = null, $state = 1, $userId = 0) {
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Youtube', 'YoutubesTable');

		// For all keys
		foreach ($pks as $pk) {
			// Load the youtube
			if (!$table->load($pk)) {
				$this->setError($table->getError());
			}

			// Verify checkout
			if ($table->checked_out == 0 || $table->checked_out == $userId) {
				// Change the state
				$table->state = $state;
				$table->checked_out = 0;
				$table->checked_out_time = $this->_db->getNullDate();

				// Check the row
				$table->check();

				// Store the row
				if (!$table->store()) {
					$this->setError($table->getError());
				}
			}
		}
		return count($this->getErrors()) == 0;
	}

	/**
	 * Method to set the sticky state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed	An optional array of primary key values to update.  If not
	 * 					set the instance property value is used.
	 * @param   integer The sticky state. eg. [0 = unsticked, 1 = sticked]
	 * @param   integer The user id of the user performing the operation.
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function stick($pks = null, $state = 1, $userId = 0) {
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Youtube', 'YoutubesTable');

		// For all keys
		foreach ($pks as $pk) {
			// Load the youtube
			if (!$table->load($pk)) {
				$this->setError($table->getError());
			}

			// Verify checkout
			if ($table->checked_out == 0 || $table->checked_out == $userId) {
				// Change the state
				$table->sticky = $state;
				$table->checked_out = 0;
				$table->checked_out_time = $this->_db->getNullDate();

				// Check the row
				$table->check();

				// Store the row
				if (!$table->store()) {
					$this->setError($table->getError());
				}
			}
		}
		return count($this->getErrors()) == 0;
	}

}
