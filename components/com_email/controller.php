<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_email
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Email Controller
 *
 * @package     Joomla.Site
 * @subpackage  com_email
 * @since       1.5
 */
class EmailController extends JControllerForm {

	public function display($cachable = false, $urlparams = false) {
		return parent::display($cachable, $urlparams);
	}

	public function send() {

		if (!isset($_POST['inputSubject'], $_POST['inputName'], $_POST['inputPhone'], $_POST['inputEmail'], $_POST['inputMessage'])) {
			$this->setRedirect(JRoute::_('index.php?option=com_email&view=email', false), JText::_('COM_EMAIL_WRONG_PARAMS'));
		}
		
		//$_POST['inputName'] = trim($_POST['inputName'];

		JSession::checkToken() or die('Invalid token');

		$oEmailModel = $this->getModel();
		$mSession = &JFactory::getSession();
		$mSession->set('inputName', trim($_POST['inputName']));
		$mSession->set('inputPhone', trim($_POST['inputPhone']));
		$mSession->set('inputEmail', trim($_POST['inputEmail']));
		$mSession->set('inputMessage', trim($_POST['inputMessage']));

		$aError = array();

//		require_once('libraries/recaptchalib.php');
//		$privatekey = "6LfyUekSAAAAALgWnGLRPe4WMZdiL8URaMU2f8hc ";
//		$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		if (!empty($_POST['inputSubject']) || trim($_POST['inputSubject']) !== '') {
			$aError['inputSubject'] = JText::_('COM_EMAIL_ROBOT_ERROR');
		}

		$_POST['inputName'] = trim($_POST['inputName']);
		if (empty($_POST['inputName']) || trim($_POST['inputName']) === '') {
			
			$aError['inputName'] = JText::_('COM_EMAIL_ENTER_NAME');
		}

		if (empty($_POST['inputEmail']) || trim($_POST['inputEmail']) === '') {
			$aError['inputEmail'] = JText::_('COM_EMAIL_ENTER_EMAIL');
		} else if (!filter_var($_POST['inputEmail'], FILTER_VALIDATE_EMAIL)) {
			$aError['wrongEmail'] = true;
		}

		if (empty($_POST['inputMessage']) || trim($_POST['inputMessage']) === '') {
			$aError['inputMessage'] = JText::_('COM_EMAIL_ENTER_MESSAGE');
		}

		if (empty($aError)) {
			$bCopy = isset($_POST['copyInput']) ? (bool) $_POST['copyInput'] : false;
			$mSend = $oEmailModel->send(date('d.m.Y H:i'), $_POST['inputName'], $_POST['inputEmail'], $_POST['inputMessage'], $bCopy);
			$aError = $mSend;
			if ($mSend['send'] === 'ok') {
				$mSession->clear('subject');
				$mSession->clear('inputName');
				$mSession->clear('inputEmail');
				$mSession->clear('inputMessage');
				$sMsg = JText::_('COM_EMAIL_SUCCESS');
				$aError = array('send' => $sMsg, 'success' => true);
			}
		} else {
			if (isset($aError['inputSubject'])) {
				$sMsg = JText::_('COM_EMAIL_ROBOT_ERROR');
			} else {
				if (isset($aError['wrongEmail'])) {
					$sMsg = JText::_('COM_EMAIL_INVALID_EMAIL');
				} else {
					$sMsg = JText::_('COM_EMAIL_FAILURE');
				}
			}
			
			$aError['notsend'] = $sMsg;
		}

		if (isset($_POST['ajax'])) {
			echo json_encode($aError);
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_email&view=email', false), implode('<br />', $aError));
		}
	}

}
