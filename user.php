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

function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a d, %h hr, %i min, %s sec');
}
function percentStr($smallvalue, $bigvalue) {
	if($bigvalue==0){return '-%';}
	$percent = round($smallvalue*100/$bigvalue,2);
	return $percent.'%';
}

###################################
## select user profile data
###################################
if(!array_key_exists('id', $_GET)){
	echo 'No user specified.';
	exit;
}

$query="
select *
  from users
  where id=$_GET[id]
  LIMIT 1
";
//the first (and teorycally only) element of this query is our user!
$user= $myDb->customSelect($query);
if($user){
	$user=$user[0];
}else{
	echo 'There is no user with such id.';
	exit;
}

###################################
## get races
###################################
$query="
select *
  from races
  where user_id=$user[id] 
  and type=".$RACETYPE->RACE."
";
$races= $myDb->customSelect($query);


###################################
## get won races
###################################
$query="
select *
  from races
  where user_id=$user[id] 
  and type=".$RACETYPE->RACE."
  and endposition = 1
";
$raceswon= $myDb->customSelect($query);
$raceswonpercent=percentStr(count($raceswon),count($races));

###################################
## get podium races
###################################
$query="
select *
  from races
  where user_id=$user[id] 
  and type=".$RACETYPE->RACE."
  and endposition < 4
  and endposition > 0
";
$racespodium = $myDb->customSelect($query);
$racespodiumpercent=percentStr(count($racespodium),count($races));
###################################
## number of practice sessions
###################################
$query="
select COUNT(*) as counts
  from races
  where 
  user_id=$user[id] 
  and type = $RACETYPE->PRACTICE";
$practicescount = $myDb->customSelect($query);
$practicescount=$practicescount[0]['counts'];
###################################
## number of qualify sessions
###################################
$query="
select COUNT(*) as counts
  from races
  where 
  where user_id=$user[id]
  and type= $RACETYPE->QUALIFY";
$qualifiescount = $myDb->customSelect($query);
$qualifiescount = $qualifiescount[0]['counts'];
###################################
###################################
## get retired unfinisced races
###################################
$query="
select *
  from races
  where user_id=$user[id]
  and type=".$RACETYPE->RACE."
  and endposition=0
";
$racesretired = $myDb->customSelect($query);
$racesretiredpercent=percentStr(count($racesretired),count($races));
###################################
## most used car
###################################
$query="
select car_id, COUNT(*) as counts
  from races
  where user_id=$user[id]
  group by car_id
  order by counts desc
";
$mostusedcar = $myDb->customSelect($query);
if($mostusedcar){
	$mostusedcar = $mostusedcar[0];
}
###################################
## most used track
###################################
$query="
select track_id, COUNT(*) as counts
  from races
  where user_id=$user[id]
  group by track_id
  order by counts desc
";
$mostusedtrack = $myDb->customSelect($query);
if($mostusedtrack){
	$mostusedtrack = $mostusedtrack[0];
}
###################################
## time on track global
###################################
$query="
SELECT sum(A.laptime) as time 
  FROM laps A
INNER
  JOIN races B
	ON B.user_id = $user[id]
";

$timeontrack = $myDb->customSelect($query);
$timeontrack = $timeontrack[0];
###################################
## time on track practice
###################################
$query="
SELECT sum(A.laptime) as time 
  FROM laps A
INNER
  JOIN races B
    ON B.type = ".$RACETYPE->PRACTICE."
    AND B.user_id = $user[id]
";
$timeontrackPractice = $myDb->customSelect($query);
$timeontrackPractice = $timeontrackPractice[0];
###################################
## time on track qualify
###################################
$query="
SELECT sum(A.laptime) as time 
  FROM laps A
INNER
  JOIN races B
    ON B.type = ".$RACETYPE->QUALIFY."
    AND B.user_id = $user[id]
";
$timeontrackQualify = $myDb->customSelect($query);
$timeontrackQualify = $timeontrackQualify[0];
###################################
## time on track race
###################################
$query="
SELECT sum(A.laptime) as time 
  FROM laps A
INNER
  JOIN races B
    ON B.type = ".$RACETYPE->RACE."
    AND B.user_id = $user[id]
";
$timeontrackRace = $myDb->customSelect($query);
$timeontrackRace = $timeontrackRace[0];
?>
<?php
require_once './header.inc.php';
?>
<table>
	<tr>
		<td>
			<table>
				<tr>
					<td><img src="./img/flags/flags_medium/<?php echo $user['nation'] ?>.png" alt="<?php echo $user['nation'] ?>" ></td>
					<td><?php echo $user['username'] ?></td>
				</tr>
				<tr>
					<td colspan="2"><img src="<?php echo $user['img']?>" width="400" alt="<?php echo $user['id'] ?>" ></td>
				</tr>

			</table>		
		</td>
		<td>
			<table>
				<tr>
					<td>Time spent on the track:</td>
					<td>
						<?php echo secondsToTime(round($timeontrack['time'],0))?>
						<br>Practicing: <?php echo secondsToTime(round($timeontrackPractice['time'],0))?>
						<br>Qualifying: <?php echo secondsToTime(round($timeontrackQualify['time'],0))?>
						<br>Racing: <?php echo secondsToTime(round($timeontrackRace['time'],0))?>
					</td>
				</tr>
				<tr>
					<td>Practice sessions:</td>
					<td><?php echo $practicescount ?></td>
				</tr>
				<tr>
					<td>Qualify sessions:</td>
					<td><?php echo $qualifiescount ?></td>
				</tr>
				<tr>
					<td>Race sessions:</td>
					<td><?php echo count($races) ?></td>
				</tr>
				<tr>
					<td>Wins:</td>
					<td><?php echo count($raceswon).' ('.$raceswonpercent.')' ?></td>
				</tr>
				<tr>
					<td>Podiums:</td>
					<td><?php echo count($racespodium).' ('.$racespodiumpercent.')' ?></td>
				</tr>
				<tr>
					<td>Retired/Not finisched:</td>
					<td><?php echo count($racesretired).' ('.$racesretiredpercent.')' ?></td>
				</tr>
				<tr>
					<td>Prefered car:</td>
					<td>
						<?php 
							if($mostusedcar){
								echo $cars->$mostusedcar['car_id']->clickableName().'<br>';
								echo $cars->$mostusedcar['car_id']->imgTag();
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Prefered track:</td>
					<td>
						<?php
							if($mostusedtrack){
								echo $tracks->$mostusedtrack['track_id']->name.'<br>';
								echo $tracks->$mostusedtrack['track_id']->imgTag();
							}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<!-- all races from this driver -->
<h1>Latest Races</h1>
<table width="98%">
	<tr>
		<th>Race id</th>
		<th>Started on</th>
		<th>Track</th>
		<th>Car</th>
		<th>Start Position</th>
		<th>Finish Position</th>
		<th>Race Type</th>
	</tr>
<?php

foreach ($races as $race){
	echo "
		<tr>
		<td>$race[id]</td>
		<td>$race[timestamp]</td>
		<td>".$tracks->$race['track_id']->clickableName()."</td>
		<td>".$cars->$race['car_id']->clickableName()."</td>
		<td>$race[startposition]</td>
		<td>";
		if($race['endposition']>0){echo $race['endposition'];}else{echo 'Retired/Not finished';}
		echo "</td>
		<td>";
		echo racetype($race['type']);
		echo"</td>
		</tr>
		";
}
?>
</table>

<!-- last race from this driver -->
<?php
//select the last race
$query="
select MAX(id) as id
  from races
  where user_id=$user[id]
";
$race = $myDb->customSelect($query);
$race= $race[0];
//select the laps from the last race
$query="
select *
  from laps
  where race_id=$race[id]
";
$laps = $myDb->customSelect($query);
?>
<h1>Laps from the last race</h1>
<table width="98%">
	<tr>
		<th>Id</th>
		<th>Laptime</th>
		<th>Fuel</th>
		<th>Position</th>
	</tr>
<?php
if($laps){
	foreach ($laps as $lap){
		echo "
			<tr>
			<td>$lap[id]</td>
			<td>$lap[laptime]</td>
			<td>$lap[fuel]</td>
			<td>$lap[position]</td>
			</tr>
			";
	}
}
?>

</table>

<?php
require_once './footer.inc.php';
?>
