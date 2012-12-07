<?php
//error_reporting( E_ALL | E_STRICT );
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
//ini_set('display_errors','On');

//require_once './config.inc.php';
//require_once './database.php';

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

$myXml=simplexml_load_file ('./xml/hostlist_reply.xml');



//var_dump($myXml);

//echo $myXml->date["validateAs"];
header ("Content-Type:text/xml");  
echo $myXml->asXML();

/*
function traverseXmlObj($xml){
	foreach($xml->children() as $elementName => $child){
		echo $elementName;
	}
}
echo 'test';
traverseXmlObj($myXml);
*/
?>
