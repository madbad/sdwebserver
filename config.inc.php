<?php
$config = new stdClass; 

$config->database = new stdClass; 
$config->database->name = '';
$config->database->host = '';
$config->database->username = '';
$config->database->password = '';

//date_default_timezone_set('Europe/London');
date_default_timezone_set('Europe/Rome');

//some constant
$RACETYPE = new stdClass;
$RACETYPE->PRACTICE = 0; 
$RACETYPE->QUALIFY = 1; //?
$RACETYPE->RACE = 2;
function racetype($num){
	switch ($num){
		case 0: return 'practice';break;
		case 1: return 'qualify';break;
		case 2: return 'race';break;
	}
}
?>
