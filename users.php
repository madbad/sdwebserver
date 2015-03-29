<?php
//error_reporting( E_ALL | E_STRICT );
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors','On');

//include required files
require_once './config.inc.php';
require_once './database.php';

//initialize the database
$myDb=new DataBase($config->database);

//select user data from database
$query='
select id
  from users
';
$users = $myDb->customSelect($query);

//start the html page
require_once './header.inc.php';
?>
	<table class="fullPage">
		<tr>
			<th>User Name</th>
			<th>Nation</th>
			<th>Member since</th>
			<th>Latest activity</th>
			
		</tr>
<?php
foreach ($users as $user){
	$myuser=new User($user['id']);
	echo "\n\t\t<tr>";
	echo "\n\t\t\t<td>".$myuser->getLink()."</td>";
	echo "\n\t\t\t<td>".$myuser->getSmallFlagImg()."</td>";
	echo "\n\t\t\t<td>".date_format(new DateTime($myuser->registrationdate), 'd M Y @ H:i')."</td>";

if($myuser->sessiontimestamp>0){
	$date1 = new DateTime("");
	$date2 = new DateTime($myuser->sessiontimestamp);
	$interval = $date1->diff($date2);
	$ago='';
	$years=$interval->y;
	$months=$interval->m;
	$days=$interval->d;
	$hours=$interval->h;
	$minutes=$interval->i;
	$done=false;
	//echo $interval->d;
	if($years > 0){
		$ago.= $years." years ";
		$done=true;
	}
	if($months > 0 && !$done){
		$ago.= $months." months ";
		$done=true;
	}
	if($days > 0 && !$done){
		$ago.= $days." days ";
		$done=true;
	}
	if($hours > 0 && !$done){
		$ago.= $hours." hours ";
		$done=true;
	}
	if($minutes > 0 && !$done){
		$ago.= $minutes." minutes ";
	}
	if($years<1 &&$months<1 && $days<1 && $hours<1 && $minutes<1){
		$ago='Now';	
	}else{
		$ago.='ago';	
	}
}else{
	$ago='Never been active';	
}

	echo "\n\t\t\t<td>".$ago."</td>";
	echo "\n\t\t</tr>";	
}
?>

	</table>

<?php
//end the html page
require_once './footer.inc.php';
?>
