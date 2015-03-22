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

//print the header for the page
require_once './header.inc.php';
?>
<table class="fullPage"><tr><td class="verticalMenu" style="width: 15em;">
	<b>Category selection</b>:
	<?php
		$firstRun = true;
		foreach ($carCategories as $carCatId => $carCategory){
			if($firstRun){
				$defaultCategoryId= $carCatId;
			}
			echo "\n<a href='?cat=".$carCatId."'>".$carCategory->name."</a>";
		}
	?>
</td>
<td>
	<h1>
		<?php
			if (array_key_exists('cat', $_GET)){
				$carCatId=$_GET['cat'];
			}else{
				$carCatId=$defaultCategoryId;
			}
			 echo $carCategories->$carCatId->name;
		 ?>
	</h1>

	<table class="fullPage">
		<thead>
			<tr>
				<th colspan="2">Most active users<br><small>In the last 7 days</small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Pilot</td><td>Races</td>
		</tr>
		<?php
			$carsql='';
			foreach ($carCategories->$carCatId->cars as $car){
				$carsql.=" OR car_id='$car'";
			}
			$carsql=substr($carsql, 4); //remove the fiirst " OR "

			$backto=time()-(7*24*60*60);
			$query="
				SELECT user_id, COUNT(*) as count
				FROM races
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND $carsql
				GROUP BY user_id
				ORDER BY COUNT(*) DESC";

			$result = $myDb->customSelect($query);
			
			if($result){
				foreach ($result as $race){
					$user =new User($race['user_id']);
					echo "<tr><td>".$user->getLink()."</td><td>$race[count]</td></tr>";
				}
			}
		?>
		</tbody>
	</table>

	<table class="fullPage">
		<thead>
			<tr>
				<th colspan="3">Bests laps<br><small>In the last 7 days</small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Track</td><td>Pilot</td><td>Laptime</td>
		</tr>
		<?php
			//select the races grouped by track
			$backto=time()-(7*24*60*60);
			$query="
				SELECT track_id, GROUP_CONCAT(id) as raceids
				FROM races
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND $carsql
				GROUP BY track_id";
			$myraces = $myDb->customSelect($query);

			//select all the laps that are about this track
			foreach ($myraces as $myrace){
				$track = $myrace['track_id'];
				$bestlaps[$track]='';
				$query="
					SELECT race_id, laptime, id, MIN(laptime) as bestlap
					FROM laps
					WHERE id = ".implode(' or id = ',explode(',',$myrace['raceids']))."
					AND UNIX_TIMESTAMP(timestamp) > $backto";
				$mylap = $myDb->customSelect($query);
				$mylap = $mylap[0];
				if($mylap['race_id']!=''){
					$query="
						SELECT *
						FROM races
						WHERE 
						id =".$mylap['race_id'] ;
					$myracelap = $myDb->customSelect($query);
					$myracelap = $myracelap[0];
					$user=new User($myracelap['user_id']);
					echo "<tr><td>".$tracks->$myrace['track_id']->clickableName()."</td><td>".$user->getLink()."</td><td>$mylap[bestlap]</td></tr>";
				}
			}
		?>
		</tbody>
	</table>

	<table class="fullPage">
		<thead>
			<tr>
				<th colspan="2">Most used Tracks<br><small>In the last 7 days</small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Track</td><td>Races</td>
		</tr>
		<?php
			$backto=time()-(7*24*60*60);
			$query="
				SELECT track_id, COUNT(*) as count
				FROM races
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND $carsql
				GROUP BY track_id
				ORDER BY COUNT(*) DESC";
			$result = $myDb->customSelect($query);
			if($result){
				foreach ($result as $race){
					echo "<tr><td>".$tracks->$race['track_id']->clickableName()."</td><td>$race[count]</td></tr>";
				}
			}
		?>
		</tbody>
	</table>

	<table class="fullPage">
		<thead>
			<tr>
				<th colspan="2">Top cars<br><small>In the last 7 days</small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Car</td><td>Races</td>
		</tr>
		<?php
			$backto=time()-(7*24*60*60);
			$query="
				SELECT car_id, COUNT(*) as count
				FROM races
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND $carsql
				GROUP BY car_id
				ORDER BY COUNT(*) DESC";

			$result = $myDb->customSelect($query);
			if($result){
				foreach ($result as $race){
					echo "<tr><td>".$cars->$race['car_id']->clickableName()."</td><td>$race[count]</td></tr>";
				}
			}
		?>
		</tbody>
	</table>
</td>
</tr></table>

<?php
require_once './footer.inc.php';
?>
