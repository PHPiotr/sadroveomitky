<?php

defined('_JEXEC') or die('Restricted access');

//JHtml::script(JUri::root() . 'media/mod_profishop_map/initialize.js');

$sModuleclassSfx = htmlspecialchars($params->get('moduleclass_sfx',''));

$sWidth = (int) $params->get('width',980);
$sHeight = (int) $params->get('height',460);
$sLatitude = $params->get('latitude',50.039296);
$sLongitude = $params->get('longitude',18.374598900000024);
$sCenterLatitude = $params->get('center_latitude',$sLatitude);
$sCenterLongitude = $params->get('center_longitude',$sLongitude);
$sTitle = $params->get('title','Diamant Sádrové omítky');
$sName = $params->get('name','Damian Kabut');
$sEmail = $params->get('email','diamant.damian@wp.pl');
$sNip = $params->get('nip','6472084898');
$sPostal = $params->get('postal','44-370');
$sCity = $params->get('city','Pszów');
$sStreet = $params->get('street','ul. Karola Miarki 37');
$sCountry = $params->get('country','Polsko');
$iZoom = (int) $params->get('zoom',15);

require JModuleHelper::getLayoutPath('mod_profishop_map', $params->get('layout', 'default'));
