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

###################################
## select race race data
###################################
if(!array_key_exists('id', $_GET)){
	echo 'No race specified.';
	exit;
}

if(!array_key_exists('id', $_GET)){
	echo 'No race specified.';
	exit;
}


require_once './header.inc.php';

//display some info about the session
$query="
select * 
  from races
  where id=".$_GET['id']."
";
$race = $myDb->customSelect($query);
$race= $race[0];


echo "<table width='96%'>";
//session type
echo "<tr><td>Session Type: </td><td>".racetype($race['type'])."</td></tr>";
echo "<tr><td>Date: </td><td>".date_format(new DateTime($race['timestamp']), 'd M Y @ H:i')."</td></tr>";
echo "<tr><td>Track: </td><td>".$tracks->{$race['track_id']}->linkTag($tracks->{$race['track_id']}->name."<br>".$tracks->{$race['track_id']}->imgTag())."</td></tr>";
echo "<tr><td>Car: </td><td>".$cars->{$race['car_id']}->linkTag($cars->{$race['car_id']}->name."<br>".$cars->{$race['car_id']}->imgTag())."</td></tr>";


//user
$user =new User($race['user_id']);
echo "<tr><td>User: </td><td>".$user->getLink()."</td></tr>";

echo "</table>";


//best lap ? maybe select it on the table?


displaySessionLaps($_GET['id'], $myDb);
raceGraph($_GET['id']);


require_once './footer.inc.php';
?>
