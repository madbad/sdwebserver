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
  user_id=$user[id]
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
	ON A.race_id = B.id
WHERE
	B.user_id=$user[id]
";

$timeontrack = $myDb->customSelect($query);
//print_t($timeontrack);
$timeontrack = $timeontrack[0];
###################################
## time on track practice
###################################
$query="
SELECT sum(A.laptime) as time 
  FROM laps A
INNER
  JOIN races B
	ON A.race_id = B.id
WHERE
	B.user_id=$user[id]
	AND B.type = ".$RACETYPE->PRACTICE."
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
	ON A.race_id = B.id
WHERE
	B.user_id=$user[id]
	AND B.type = ".$RACETYPE->QUALIFY."
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
	ON A.race_id = B.id
WHERE
	B.user_id=$user[id]
	AND B.type = ".$RACETYPE->RACE."
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
						<?php 
							echo secondsToTime(round($timeontrack['time'],0));
							$P = percentStr($timeontrackPractice['time'],$timeontrack['time']);
							$Q = percentStr($timeontrackQualify['time'],$timeontrack['time']);
							$R = percentStr($timeontrackRace['time'],$timeontrack['time']);
							echo "<br>
							<table style='width:100%;height:1em;padding:0em;margin:0em;'>
								<tr>
									<td title='Practice $P' style='width:$P;background-color:grey;padding:0em;'></td>
									<td title='Qualify $Q' style='width:$Q;background-color:orange;padding:0em;'></td>
									<td title='Race $R' style='width:$R;background-color:green;padding:0em;'></td>
								</tr>
							</table>
							";
						?>

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
								echo $cars->$mostusedcar['car_id']->clickableImgTag();
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Prefered track:</td>
					<td>
						<?php
							if($mostusedtrack){
								echo $tracks->$mostusedtrack['track_id']->clickableImgTag();
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
		<th>Finish Position<br>(gain/loss)</th>
	</tr>
<?php

foreach ($races as $race){
	echo "
		<tr>
		<td>$race[id]</td>
		<td>".date_format(new DateTime($race['timestamp']), 'd M Y @ H:i')."</td>
		<td>".$tracks->$race['track_id']->clickableImgTag()."</td>
		<td>".$cars->$race['car_id']->clickableImgTag()."</td>
		<td>";
		if($race['endposition']>0){
			echo $race['endposition'];
			$gain = $race['startposition']-$race['endposition'];
			if($gain>=0){
				echo " <sup style='color:green;'>(+$gain)</sup>";
			}else{
				echo "<sup style='color:red;'>($gain)</sup>";			
			}

		}else{
			echo 'Retired/Not finished';
		}
	echo "</td></tr>";
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
<h1>Laps from the last session (practice, qualify or race)</h1>
<table width="98%">
	<tr>
		<th>Id</th>
		<th>Laptime</th>
		<th>Fuel</th>
		<th>Position</th>
		<th>Setup File</th>		
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
			<td><a href='./downloadsetup.php?id=$lap[id]' download='car-setup.xml'>Setup</a></td>
			</tr>
			";
	}
}
?>

</table>
<?php
/*
foreach ($trackCategories as $trackCategory){
	//print_r($trackCategory);
	
	echo "\n<br><h1>".$trackCategory->name."</h1>";
	foreach ($trackCategory->tracks as $trackId){
		$track = $tracks->$trackId;
		echo "\n".$track->imgTag()."";
	}
	
} 
*/
?>


<?php
require_once './footer.inc.php';
?>
