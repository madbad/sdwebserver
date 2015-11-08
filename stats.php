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

//
$params =  new stdClass;
$params->lap_user_id =2;
//$params->lap_track ='';
//$params->lap_race_type ='';
$params->lap_track_name ='Pinabashi Park';
//$params->lap_track_name ='Espie';
//$params->lap_track_name ='Tennessee Half Mile Arena';
//$params->lap_car_name ='sc-lynx-220';
//$params->lap_car_category ='';


//-----------------------------
$table='lap_list';
$out= $myDb->select($params, $table);
$series=[];
$series['laptime']='';
$series['lapid']='';

$racelapstime=0;
$racelapcount=0;
$practicelapstime=0;
$practicelapcount=0;

foreach ($out as $val){
	$series['laptime'].= $val['lap_laptime'].',';
	$series['lapid'].= $val['lap_id'].',';
	
	//calculate avverange race lap
	if($val['lap_race_type']==2){
		$racelapstime += $val['lap_laptime'];
		$racelapcount++;
	}
	//calculate avverange practive lap
	if($val['lap_race_type']==1){
		$practicelapstime += $val['lap_laptime'];
		$practicelapcount++;
	}
}
$raceavverange= $racelapstime/$racelapcount;
$practiceavverange=$practicelapstime/$practicelapcount;
$best = $myDb->selectMin($params, $table,'lap_laptime');
?>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="style.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<SCRIPT>
$(function () {
        $('#chart').highcharts({
            title: {
                text: 'Laptimes at <?php echo $params->lap_track_name ?>',
                x: -20 //center
            },
            subtitle: {
                text: 'Track: ',
                x: -20
            },
            /*
            xAxis: {
                categories: [<?php echo $series['lapid'] ?>]
            },
            */
            xAxis: {
                categories: [<?php echo $series['lapid'] ?>]
            },

            yAxis: {
                title: {
                    text: 'Time (seconds)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                },{
				color: 'red', // Color value
				dashStyle: 'solid', // Style of the plot line. Default to solid
				value: '<?php echo $raceavverange ?>', // Value of where the line will appear
				width: '2', // Width of the line    
				  label: { 
					text: 'Race avverrange <?php echo round($raceavverange,3) ?>', // Content of the label. 
					align: 'left', // Positioning of the label. 
					x: +10, // Amount of pixels the label will be repositioned according to the alignment.
					style:{
                    fontSize: '25px'
                }     
				  }
			  },{
				color: 'orange', // Color value
				dashStyle: 'solid', // Style of the plot line. Default to solid
				value: '<?php echo $practiceavverange ?>', // Value of where the line will appear
				width: '2', // Width of the line  
				  label: { 
					text: 'Practice avverrange <?php echo round($practiceavverange,3) ?>', // Content of the label. 
					align: 'left', // Positioning of the label. 
					x: +10, // Amount of pixels the label will be repositioned according to the alignment. 
					style:{
                    fontSize: '25px'
                }     
				  }  
			  },{
				color: 'green', // Color value
				dashStyle: 'solid', // Style of the plot line. Default to solid
				value: '<?php echo $best ?>', // Value of where the line will appear
				width: '2', // Width of the line  
				  label: { 
					text: 'Best <?php echo round($best,3) ?>', // Content of the label. 
					align: 'left', // Positioning of the label. 
					x: +10, // Amount of pixels the label will be repositioned according to the alignment. 
					style:{
                    fontSize: '25px'
                }     
				  }  
			  }]
            },
            tooltip: {
                valueSuffix: 'sec'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Your laps',
                data: [<?php echo $series['laptime'] ?>]
            }],
        });
    });
</SCRIPT>
</HEAD>
<BODY>
	<img src="http://www.speed-dreams.org/images/speed-dreams.png" width="300" heigth="138"/>
	<table>
		<tr>
			<td><img src="http://openclipart.org/people/picapica/full-face_helmet.svg" width="100"/></td>
			<td><?php echo $params->lap_user_id; ?></td>
		</tr>
		<tr>
			<td><img src="http://openclipart.org/people/gnokii/checkeredflag.svg" width="100"/></td>
			<td><?php echo $params->lap_track_name; ?></td>
		</tr>
		<!--
		<tr>
			<td><img src="http://openclipart.org/people/netalloy/rally-car.svg" width="100"/></td>
			<td><?php echo $params->lap_car_name; ?></td>
		</tr>
		-->
		<!--
		<tr>
			<td>&#126; Practice lap: </td>
			<td><?php echo round($practiceavverange,3) ?></td>
		</tr>
		<tr>
			<td>&#126; Race lap: </td>
			<td><?php echo round($raceavverange,3) ?></td>
		</tr>
		<tr>
			<td>Best lap: </td>
			<td>
				<?php
					echo round($best,3);
				?>
			
			</td>
		</tr>
		-->
		<tr>
			<td colspan="2" id="chart">
			</td>
		</tr>
	</table>

</BODY>
</HTML>
