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

/*
$myDb=new DataBase($config->database);
$myDb->query('
	from: tablename
	select: id,name,data
	conditions:[
		operator = and,
		id = test,
		data = bho,
		ciro > me
	]
');
*/

/*
$myXml=simplexml_load_file ('./xml/hostlist_reply.xml');

//traverseXmlObj($myXml->success->hostlist->host);
XmlValidate($myXml);
*/
$myXml=new MyXml(file_get_contents('./xml/hostlist_reply.xml'));

//echo $myXml->success->hostlist[0]->validate();
//echo $myXml->validate();

?>
