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
	echo 'No track specified.';
	exit;
}
$car = $cars->{$_GET['id']};

require_once './header.inc.php';
?>
<table class="fullPage">
	<tr>
		<td>
			<?php 
			echo '<h1>'.$car->name.'</h1>';	
			?>
			<table>
				<tr>
					<td>Category</td>
					<td><?php echo $car->category; ?></td>
				</tr>
				<tr>
					<td>Prewiev</td>
					<td><?php echo $car->imgTagFull(); ?></td>
				</tr>
				<tr>
					<td>Engine</td>
					<td><?php echo $car->engine; ?></td>
				</tr>
				<tr>
					<td>Drivetrain</td>
					<td><?php echo $car->drivetrain; ?></td>
				</tr>
				<tr>
					<td>Width</td>
					<td><?php echo $car->width; ?></td>
				</tr>
				<tr>
					<td>Lenght</td>
					<td><?php echo $car->lenght; ?></td>
				</tr>
				<tr>
					<td>Fuel tank</td>
					<td><?php echo $car->fueltank; ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php
require_once './footer.inc.php';
?>
