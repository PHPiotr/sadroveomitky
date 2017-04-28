<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_galleries
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Banner controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_youtubes
 * @since       1.6
 */
class GalsControllerGal extends JControllerForm {

	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_GALS_GALS';

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array()) {
		$user = JFactory::getUser();
		$filter = $this->input->getInt('filter_category_id');
		$categoryId = JArrayHelper::getValue($data, 'catid', $filter, 'int');
		$allow = null;

		if ($categoryId) {
			// If the category has been passed in the URL check it.
			$allow = $user->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if ($allow === null) {
			// In the absence of better information, revert to the component permissions.
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id') {
		$user = JFactory::getUser();
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId) {
			$categoryId = isset($this->getModel()->getItem($recordId)->catid) ? (int) $this->getModel()->getItem($recordId)->catid : 0;
		}

		if ($categoryId) {
			// The category has been set. Check the category permissions.
			return $user->authorise('core.edit', $this->option . '.category.' . $categoryId);
		} else {
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   string  $model  The model
	 *
	 * @return  boolean  True on success.
	 *
	 * @since	2.5
	 */
	public function batch($model = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Gal', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_gals&view=gals' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	public function remove() {
		$iId = $this->input->getInt('id');
		$iPhotoId = (int) $_POST['photo_id'];
		$sPhoto = $_POST['photo'];
		$bMain = (bool) $_POST['main'];
		$oGalModel = $this->getModel('Gal');
		$oGalModel->removePhoto($iId, $iPhotoId, $sPhoto, $bMain);
	}

}
