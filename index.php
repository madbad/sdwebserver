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


// select interested period
if(array_key_exists('period', $_COOKIE)){
	$period = $_COOKIE['period'];
}
if(array_key_exists('period',$_GET)){
	setcookie( "period", $_GET['period'], time()+(60*60*24*30) );
	$period = $_GET['period'];
}else{
	$period='year';
}

switch ($period){
case 'today'://today
	$datediff=(1*24*60*60);
	$backto=time()-$datediff;
	$periodString ='In the last day';
	break;
case 'week'://last week
	$datediff=(7*24*60*60);
	$backto=time()-$datediff;
	$periodString ='In the last week';
	break;
case 'month'://last month
	$datediff=(30*24*60*60);
	$backto=time()-$datediff;
	$periodString ='In the last month';
	break;
case 'year'://last year
	$datediff=(365*24*60*60);
	$backto=time()-$datediff;
	$periodString ='In the last year';
	break;
/*
case 'date'://from this date
	$datediff=(7*24*60*60);
	$backto=time()-$datediff;
	$periodString ='From '.date('d-m-Y', $backto);
	break;
*/
case 'allTime'://always
	$datediff=(50000*24*60*60);
	$backto=time()-$datediff;
	$periodString ='all time';
	break;

}

//print the header for the page
require_once './header.inc.php';


//select the category to display
if (array_key_exists('cat', $_GET)){
	$carCatId=$_GET['cat'];
}

//reorder the cetegories by name
$carCategoriesList = get_object_vars($carCategories);
ksort($carCategoriesList);

?>
<table class="fullPage"><tr><td class="verticalMenu" style="width: 15em;">
	<b>Category selection</b>:
	<?php
		/*################################
		  ## generate the car category selection menu
		  ################################
		 */
		foreach ($carCategoriesList as $id => $category){
			//if the category contain no cars we do no consider it
			//todo: should we display only officially released ones?
			if(count($category->cars) > 0){
				global $carCatId;
				//if no category has been chosen by the user, used the first valid (non empty) one
				if($carCatId==''){
					$carCatId= $id;
				}
				
				//set a splecial class for the menu item that represent the currently selected class
				if($carCatId==$id){
					$class='class="selected"';
				}else{
					$class='';
				}
				//echo "\n<a href='?cat=".$id."' $class>".$category->name."</a>";
				echo "\n".'<a href="'.rewriteUrl('cat',$id).'"'."$class>".$category->name."</a>";
			}			
		}
		

		/*################################
		  ## generate a string that find all cars id of this carCategory
		  ################################
		 */

		$carsql='';
		foreach ($carCategories->$carCatId->cars as $car){
			$carsql.=" OR B.car_id='$car'";
		}

		$carsql=substr($carsql, 4); //remove the first " OR "

		//UGLY: there is some category that have no car assigned so create a fake $carsql for them
		//to prevent errors in the generated queries
		if($carsql==''){
			$carsql=" B.car_id='NonExistentCarIdFindThisIfYouCan'";
		}
	?>
</td>
<td>
	Period:
	<a href="<?php echo rewriteUrl('period','today'); ?>">Today</a>
	<a href="<?php echo rewriteUrl('period','week'); ?>">Week</a>
	<a href="<?php echo rewriteUrl('period','month'); ?>">Month</a>
	<a href="<?php echo rewriteUrl('period','year'); ?>">Year</a>
	<a href="<?php echo rewriteUrl('period','allTime'); ?>">AllTime</a>
	<h1>
		<?php
			 echo $carCategories->$carCatId->name;
		 ?>
	</h1>

	<table class="fullPage">
		<thead>
			<tr>
				<th colspan="2">Most active users<br><small><?php echo $periodString; ?></small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Pilot</td><td>Races</td>
		</tr>
		<?php
			/*
			################################
			## MOST ACTIVE USER OF THIS CATEGORY BASED ON LAPS RUN
			## WITH A CAR OF THIS CATEGORY
			################################
			*/

			$query="
				SELECT B.user_id, COUNT(*) as count
				FROM races B
				WHERE UNIX_TIMESTAMP(B.timestamp) > $backto
				AND ($carsql)
				GROUP BY B.user_id
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
				<th colspan="7">Bests lap for each track<br><small><?php echo $periodString; ?></small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Track</td><td>Pilot</td><td>Car</td><td>Laptime</td><td>Weather</td><td>Date</td><td>Session</td>
		</tr>
		<?php
			/*
			################################
			## SELECT THE BEST LAPS FOR EACH TRACK
			## WITH A CAR OFT HIS CATEGORY
			################################
			*/

			$query="
			SELECT A.race_id, B.track_id, B.car_id, B.user_id, B.timestamp, A.wettness, min(A.laptime) as bestlap
			  FROM laps A
			INNER
			  JOIN races B
				ON A.race_id = B.id
			WHERE
				UNIX_TIMESTAMP(B.timestamp) > $backto
				AND ($carsql)
			GROUP BY
				B.track_id,
				A.wettness
			";
			$mylaps = $myDb->customSelect($query);
			foreach ($mylaps as $mylap){
				$user=new User($mylap['user_id']);
				echo "<tr>";
				echo "<td>";
				$track = $mylap['track_id'];
				echo getTrack($track)->clickableName();
				//echo $tracks->$mylap['track_id']->clickableName();
				echo "</td>";
				echo "<td>";
				echo $user->getLink();
				echo "</td>";
				echo "<td>";
				$car = $mylap['car_id'];
				echo getCar($car)->clickableName();
				//echo $cars->$mylap['car_id']->clickableName();
				echo "</td>";
				echo "<td>";
				echo formatLaptime($mylap['bestlap']);
				echo "</td>";
				echo "<td>";
				echo weatherTag($mylap['wettness']);
				echo "</td>";
				echo "<td>";
				echo $mylap['timestamp'];
				echo "</td>";
				echo "<td>";
				echo "<a href='./race.php?id=".$mylap['race_id']."'>#".$mylap['race_id']."</a>";
				echo "</td></tr>";
			}
		?>
		</tbody>
	</table>

	<table class="fullPage">
		<thead>
			<tr>
				<th colspan="2">Most used Tracks<br><small><?php echo $periodString; ?></small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Track</td><td>Races</td>
		</tr>
		<?php
			$query="
				SELECT track_id, COUNT(*) as count
				FROM races B
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND ($carsql)
				GROUP BY B.track_id
				ORDER BY COUNT(*) DESC";
			$result = $myDb->customSelect($query);
			if($result){
				foreach ($result as $race){
					$track= $race['track_id'];
					echo "<tr><td>".getTrack($track)->clickableName()."</td><td>$race[count]</td></tr>";
				}
			}
		?>
		</tbody>
	</table>

	<table class="fullPage">
		<thead>
			<tr>
				<th colspan="2">Top cars<br><small><?php echo $periodString; ?></small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Car</td><td>Races</td>
		</tr>
		<?php
			$query="
				SELECT car_id, COUNT(*) as count
				FROM races B
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND ($carsql)
				GROUP BY B.car_id
				ORDER BY COUNT(*) DESC";

			$result = $myDb->customSelect($query);
			if($result){
				foreach ($result as $race){
					$car = $race['car_id'];
					echo "<tr><td>".getCar($car)->clickableName()."</td><td>$race[count]</td></tr>";
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
