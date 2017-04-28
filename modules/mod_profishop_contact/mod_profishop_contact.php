<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/helper.php';
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$sNameLabel = $params->get('name_label', 'Name');
$sPhoneLabel = $params->get('phone_label', 'Phone');
$sEmailLabel = $params->get('email_label', 'Email');
$sMessageLabel = $params->get('message_label', 'Message');
$sButtonText = $params->get('button_text', 'Send Message');
$sCopyText = $params->get('copy_label', 'Send copy of the message to me');

if (isset($_POST['submit'])) {
	ModProfishopContactHelper::processForm($params);
}
require JModuleHelper::getLayoutPath('mod_profishop_contact', $params->get('layout', 'default'));
