<?php
//error_reporting( E_ALL | E_STRICT );
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors','On');

//include required files
require_once './config.inc.php';
require_once './database.php';
require_once './utility.php';
require_once './xml/validation.php';

//intialize the logger
$log = new Logger('./logs/webserver.log');

//initialize the database
$myDb=new DataBase($config->database);

//read the xml
$xml=new MyXml(file_get_contents('./xml/host_register.xml'));



//goto the real data
$xmlData=$xml->request;

//validate the data
$xmlData->validate();

//and save it to the database
$xmlData->saveToDb();

?>
