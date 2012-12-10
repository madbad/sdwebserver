<?php
$validator['null'] = '';
$validator['integer'] = "^(\d*)$";
$validator['unixDateTime'] = "^(\d*)$";
$validator['bolean'] = '^(true|false)$';
$validator['raceStatus'] =  "^(waiting|racing|ended)$";
$validator['seconds'] = "^(\d*)$";
$validator['raceLenghtType'] =  "^(laps|time)$";
$validator['comparisonOperator'] =  "^(=|!=|<|>|<>)$";
$validator['orderingType'] =  "^(ascending|descending)$";
$validator['everything'] =  "^(.*)$";

$validator['commonChars'] = '';
$validator['commonCharsAndNumbers'] = '';
$validator['ip'] = "^([0-9]{1,3}.){3}[0-9]{1,3}$";
$validator['country'] = '';
$validator['operatingSystems'] = "^(linux|mac|windows|unknow)$";
$validator['carCategory'] = '';
$validator['track'] = '';
?>
