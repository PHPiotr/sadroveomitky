<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
jimport('joomla.filesystem.folder');

class com_galsInstallerScript {

	/**
	 * method to install the component
	 *
	 * @return void
	 */
	public function install($parent) {
		$parent->getParent()->setRedirectURL('index.php?option=com_gals');

		if (!JFolder::create(JPATH_ROOT . DS . 'images' . DS . 'galleries')) {
			$this->setError(JText::_('COM_GALS_INSTALL_FOLDER_NOT_CREATED'));
		}
	}

	public function uninstall($parent) {
		if (!JFolder::delete(JPATH_ROOT . DS . 'images' . DS . 'galleries')) {
			$this->setError(JText::_('COM_GALS_INSTALL_FOLDER_NOT_DELETED'));
		}
	}

}
?>
