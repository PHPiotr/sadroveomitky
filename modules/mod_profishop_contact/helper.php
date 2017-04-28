<?php

defined('_JEXEC') or die;
class ModProfishopContactHelper extends JModuleHelper {

	public static function processForm(&$params) {

		JSession::checkToken() or die('Invalid Token');

		$oSession = &JFactory::getSession();

		$jinput = JFactory::getApplication()->input;

		$inputSubject = $jinput->post->get('inputSubject', '', 'string');
		$inputName = $jinput->post->get('inputName', '', 'string');
		$inputPhone = $jinput->post->get('inputPhone', '', 'string');
		$inputEmail = $jinput->post->get('inputEmail', '', 'string');
		$inputMessage = $jinput->post->get('inputMessage', '', 'string');
		$inputCopy = $jinput->post->get('copyInput', false, 'bool');

		$aError = array();

		if (isset($_POST['submit'])) {

			if (!empty($inputSubject) || trim($inputSubject) != '') {
				$sSpamAttempt = $params->get('spam_attempt', 'Your message could not be sent. Please try again.');
				$aError[] = $sSpamAttempt;
				$oSession->set('error', $sSpamAttempt);
			}

			if (empty($inputName) || trim($inputName) == '') {
				$sNoName = $params->get('no_name', 'Please enter your name.');
				$aError[] = $sNoName;
				$oSession->set('name', $sNoName);
			} else {
				$oSession->set('is-name', $inputName);
			}

			if (!empty($inputPhone) || trim($inputPhone) != '') {
				$oSession->set('is-phone', $inputPhone);
			}

			if (empty($inputEmail) || trim($inputEmail) == '') {
				$sNoEmail = $params->get('no_email', 'Please enter your email address.');
				$aError[] = $sNoEmail;
				$oSession->set('email', $sNoEmail);
			} else {
				$preg = '/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/';
				if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL) || !preg_match($preg, $inputEmail)) {
					$sNoValidEmail = $params->get('invalid_email', 'Please provide valid email address.');
					$aError[] = $sNoValidEmail;
					$oSession->set('email', $sNoValidEmail);
				}
				$oSession->set('is-email', $inputEmail);
			}

			if (empty($inputMessage) || trim($inputMessage) == '') {
				$sNoMessage = $params->get('no_message', 'Please enter your message.');
				$aError[] = $sNoMessage;
				$oSession->set('message', $sNoMessage);
			} else {
				$oSession->set('is-message', $inputMessage);
			}

			if (empty($aError)) {

				$sToEmail = $params->get('to_email', 'diamant.damian@wp.pl');

				$oMailer = JFactory::getMailer();
				$oMailer->isHTML(true);
				$oMailer->Encoding = 'base64';
				$oMailer->From = $inputEmail;
				$oMailer->FromName = $inputName;
				$oMailer->setSubject('DIAMANT');
				$aRecipient = $inputCopy === true ? array($sToEmail, $inputEmail) : array($sFromEmail);
				$oMailer->addRecipient($aRecipient);

				$body = "Nadawca: {$inputName}<br />";
				$body .= "Telefon: {$inputPhone}<br /><hr />";
				$body .= "<div>{$inputMessage}</div>";
				$body .= "<hr /><div>{$_SERVER['REMOTE_ADDR']}<br />{$_SERVER['HTTP_USER_AGENT']}</div>";

				$oMailer->setBody($body);
				$bSend = $oMailer->Send();
				if ($bSend) {
					$sSuccess = $params->get('success_text');
					$aError[] = $sSuccess;
					$oSession->set('success', $sSuccess);
					
					header('Location:' . $_SERVER['HTTP_REFERER']);
					exit;
				}
			}
			$sError = $params->get('error_text');
			$aError[] = $sError;
			if (!$oSession->get('error')) {
				$oSession->set('error', $sError);
			}
			header('Location:' . $_SERVER['HTTP_REFERER']);
			exit;
		}
	}

	/**
	 * A helper method to clear sessions after page refresh
	 * @param string $sName		Name of checked session
	 */
	public static function processSession($sName) {

		$oSession = &JFactory::getSession();
		$sSession = $oSession->get($sName);
		$sIssetSession = $oSession->get('isset-' . $sName);

		if (!empty($sSession)) {
			if (!empty($sIssetSession)) {
				$oSession->set($sName, '');
				$oSession->set('isset-' . $sName, '');
			}
			$oSession->set('isset-' . $sName, true);
		}
	}

}
