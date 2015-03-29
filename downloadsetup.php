<?php
//error_reporting( E_ALL | E_STRICT );
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors','On');

//include required files
require_once './config.inc.php';
require_once './database.php';

//initialize the database
$myDb=new DataBase($config->database);

$query="
SELECT A.setup 
  FROM races A
INNER
  JOIN laps B
	ON B.race_id = A.id
WHERE
  B.id = $_GET[id]
";

$carsetup = $myDb->customSelect($query);
$carsetup = $carsetup[0]['setup'];
header("Content-Type:text/xml");
echo $carsetup;
?>
