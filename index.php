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
$backto=time()-(7*24*60*60);


//print the header for the page
require_once './header.inc.php';


//select the category to display
if (array_key_exists('cat', $_GET)){
	$carCatId=$_GET['cat'];
}

?>
<table class="fullPage"><tr><td class="verticalMenu" style="width: 15em;">
	<b>Category selection</b>:
	<?php
		/*################################
		  ## generate the car category selection menu
		  ################################
		 */
		foreach ($carCategories as $id => $category){
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
				echo "\n<a href='?cat=".$id."' $class>".$category->name."</a>";
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
	<h1>
		<?php
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
				AND $carsql
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
				<th colspan="4">Bests lap for each track<br><small>In the last 7 days</small></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>Track</td><td>Pilot</td><td>Car</td><td>Laptime</td>
		</tr>
		<?php
			/*
			################################
			## SELECT THE BEST LAPS FOR EACH TRACK
			## WITH A CAR OFT HIS CATEGORY
			################################
			*/

			$query="
			SELECT B.track_id, B.car_id, B.user_id, min(A.laptime) as bestlap
			  FROM laps A
			INNER
			  JOIN races B
				ON A.race_id = B.id
			WHERE
				UNIX_TIMESTAMP(B.timestamp) > $backto
				AND $carsql
			GROUP BY
				B.track_id
			";
			$mylaps = $myDb->customSelect($query);
			foreach ($mylaps as $mylap){
				$user=new User($mylap['user_id']);
				echo "<tr>";
				echo "<td>";
				echo $tracks->$mylap['track_id']->clickableName();
				echo "</td>";
				echo "<td>";
				echo $user->getLink();
				echo "</td>";
				echo "<td>";
				echo $cars->$mylap['car_id']->clickableName();
				echo "</td>";
				echo "<td>";
				echo $mylap['bestlap'];
				echo "</td></tr>";
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
			$query="
				SELECT track_id, COUNT(*) as count
				FROM races B
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND $carsql
				GROUP BY B.track_id
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
			$query="
				SELECT car_id, COUNT(*) as count
				FROM races B
				WHERE UNIX_TIMESTAMP(timestamp) > $backto
				AND $carsql
				GROUP BY B.car_id
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
