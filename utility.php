<?php
###################################
## Logger utility: save logs to a file
###################################
class Logger
{
	function Logger($logFile){
		$this->logFile=$logFile;

		//backup the old log
		$this->backupOldLog();
		
		//check if the file exist or create it
		if(!is_readable($logFile)){
			fopen($logFile, 'w') 
				or die('<b>Error</b>: Unable to create logFile '.$logFile);
		}
	}

	public function log($type, $text) {
		$time = date("d/m/Y G:i:s");
		$log = '('.$type.') '.$time.' # '.$text."\n";
		$this->writeToLogFile($log);
	}

	public function error($text) {
		$this->log('EE', $text);
	}

	public function info($text){
		$this->log('II', $text);
	}

	public function warn($text){
		$this->log('WW', $text);
	}
	public function writeToLogFile($text){
		file_put_contents($this->logFile, $text, FILE_APPEND)
			or die ('<b>Error</b>:  Unable to write to the log file');
	}
	public function emptyLogFile(){
		file_put_contents($this->logFile, "\n" )
			or die ('<b>Error</b>:  Unable to write to the log file');
	}
	public function backupOldLog(){
		rename($this->logFile, $this->logFile.'.'.time().'.backup.txt');
	}


}
/* ================================================
 * make xml data saveable on database
 * 
 * ================================================*/
 /**
    @param:
        $xml: SimpleXMLElement
        $force: set to true to always create 'text', 'attribute', and 'children' even if empty
    @return
        object with attributs:
            (string) name: XML tag name
            (string) text: text content of the attribut name
            (array) attributes: array witch keys are attribute key and values are attribute value
            (array) children: array of objects made with xml2obj() on each child
**/
 function xmlObj($xmlstring){
	$obj = json_decode(json_encode(simplexml_load_string($xmlstring,'SimpleXMLElement',LIBXML_NOCDATA )));
	//$obj = simplexml_load_string($xmlstring);
	$obj->saveToDb = function(){
		global $myDb;
		$myDb->insert($this, $table);
	};
	return $obj;
}

###################################
## extract content of a json txt file an d return an object of the json string
###################################
function jsonTxtFileToObj($fileUrl, $objClass){
	$myfile = fopen($fileUrl, "r") or die("Unable to open file!");
	$text = fread($myfile,filesize($fileUrl));
	fclose($myfile);
	$text= str_replace(", '", ', "',$text);
	$text= str_replace("',", '",',$text);
	$text= str_replace("{'", '{"',$text);
	$text= str_replace("'}", '"}',$text);
	$text= str_replace("['", '["',$text);
	$text= str_replace("']", '"]',$text);
	$text= str_replace("':", '":',$text);
	$text= str_replace(": '", ': "',$text);
	$objects = json_decode($text);
	$newObjects= new stdClass();
	foreach ($objects as $key => $value){
		$newObjects->$key = new $objClass($value);
	}
	return $newObjects;
}
###################################
## get car categories info from the json text
###################################
$carCategories=jsonTxtFileToObj("./data/carCategories.txt", 'CarCategory');
$cars=jsonTxtFileToObj("./data/cars.txt", 'Car');
$trackCategories=jsonTxtFileToObj("./data/trackCategories.txt", 'TrackCategory');
$tracks=jsonTxtFileToObj("./data/tracks.txt", 'Track');

###################################
## 
###################################
function secondsToTime($seconds) {
	/*
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a d, %h hr, %i min, %s sec');
    */
	$date1 = new DateTime("@0");
	$date2 = new DateTime("@$seconds");
	$interval = $date1->diff($date2);
	$str='';
	$years=$interval->y;
	$months=$interval->m;
	$days=$interval->d;
	$hours=$interval->h;
	$minutes=$interval->i;

	if($years > 0){
		$str.= $years." years ";
	}
	if($months > 0){
		$str.= $months." months ";
	}
	if($days > 0){
		$str.= $days." days ";
	}
	if($hours > 0){
		$str.= $hours." hours ";
	}
	if($minutes > 0){
		$str.= $minutes." minutes ";
	}
	return $str;
}
###################################
## 
###################################
function percentStr($smallvalue, $bigvalue) {
	if($bigvalue==0){return '-%';}
	$percent = round($smallvalue*100/$bigvalue,0);
	return $percent.'%';
}
?>
