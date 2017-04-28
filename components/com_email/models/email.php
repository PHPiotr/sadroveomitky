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
 * email model for the Joomla email component.
 *
 * @package     Joomla.Site
 * @subpackage  com_email
 * @since       1.6
 */
class EmailModelEmail extends JModelForm {

    public function getForm($data = array(), $loadData = true) {
	$form = $this->loadForm('com_email.email', 'email', array('load_data' => $loadData));
	if (empty($form)) {
	    return false;
	} else {
	    return $form;
	}
    }

    public function getScript() {
	return 'components/com_email/models/forms/email.js';
    }

    public function send($sSubject, $sFromName, $sMailFrom, $sMsg, $bCopy) {

	$aError = array();

	$oForm = $this->getForm($data = array(), $loadData = true);

	$mValidation = $this->validate($oForm, array(
	    'inputName' => trim($sFromName),
	    'inputEmail' => trim($sMailFrom),
	    'inputMessage' => trim($sMsg)), null);

	if ((bool) $mValidation !== false) {

	    $oMailer = JFactory::getMailer();
	    $oMailer->isHTML(true);
	    $oMailer->Encoding = 'base64';
	    $oMailer->From = $sMailFrom;
	    $oMailer->FromName = $sFromName;
	    $oMailer->setSubject($sSubject);
	    $aRecipient = $bCopy === true ? array(JFactory::getConfig()->get('mailfrom'), $sMailFrom) : array(JFactory::getConfig()->get('mailfrom'));
	    $oMailer->addRecipient($aRecipient);

	    $body = "<h1>DIAMANT</h1>";
	    if(!empty($sSubject)){
		$body .= "<h2>{$sSubject}</h2>";
	    }
	    $body .= "<div>{$sMsg}</div>";
	    $body .= "<hr /><div>{$_SERVER['REMOTE_ADDR']}<br />{$_SERVER['HTTP_USER_AGENT']}</div>";

	    $oMailer->setBody($body);
//	    $oMailer->AddEmbeddedImage(JPATH_COMPONENT . '/assets/logo.png', 'logo_id', 'logo.png', 'base64', 'image/png');
	    $mSend = $oMailer->Send();
	    if ($mSend !== true) {
		$aError['send'] = $mSend->message;
	    } else {
		$aError['send'] = 'ok';
		$aError['copy'] = $bCopy === true ? true : false;
	    }
	} else {
	    $aError['send'] = JText::_('COM_EMAIL_INVALID_FORM');
	}
	
	return $aError;
    }

    protected function loadFormData() {
	// Check the session for previously entered form data.
	$data = JFactory::getApplication()->getUserState('com_email.edit.email.data', array());
	return $data;
    }

}
