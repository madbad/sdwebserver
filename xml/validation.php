<?php

$dataInfo['null']					= array('',	'');
$dataInfo['integer']				= array('INT',		"^(\d*)$");
$dataInfo['dateTime']				= array('INT',		"^(\d*)$");//DATETIME
$dataInfo['bolean']					= array('BOOLEAN',	'^(true|false)$');
$dataInfo['raceStatus']				= array('TEXT',		"^(waiting|racing|ended)$");
$dataInfo['seconds']				= array('INT',		"^(\d*)$");
$dataInfo['raceLenghtType']			= array('TEXT',		"^(laps|time)$");
$dataInfo['comparisonOperator']		= array('TEXT',		"^(=|!=|<|>|<>)$");
$dataInfo['orderingType']			= array('TEXT',		"^(ascending|descending)$");
$dataInfo['everything']				= array('TEXT',		"^(.*)$");

$dataInfo['commonChars']			= array('TEXT',		'');
$dataInfo['commonCharsAndNumbers']	= array('TEXT',		'');
$dataInfo['ip']						= array('TEXT',		"^([0-9]{1,3}.){3}[0-9]{1,3}$");
$dataInfo['country']				= array('TEXT',		'');
$dataInfo['operatingSystems']		= array('TEXT',		"^(linux|mac|windows|unknow)$");
$dataInfo['carCategory']			= array('TEXT',		'');
$dataInfo['track']					= array('TEXT',		'');

$dataInfo['requestType']			= array('TEXT',		"^(host_register_request|host_list_reply)$");

?>
