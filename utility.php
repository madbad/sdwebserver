<?php
class Logger
{
	function Logger($logFile){
		$this->logFile=$logFile;
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

}


class MyXml extends SimpleXMLElement
{
/*
	function MyXml($file){
		simplexml_load_file ($file);
	}
*/
	public function traverse() {

	}

	public function validate($level=0, $parent=null) {
		global $validator;
		$result=true.'hgfhhf';

		$level+=1;
		if ($parent!=''){
			$parent.='_';
		}
		foreach($this as $Item){
			//echo str_repeat ( '-' , $level );
			$newParent= $parent.$Item->getName();
			echo $newParent;
			echo ' : ';

			$index=$Item->attributes()->validateAs;
			$regExp= '/'.$validator["$index"].'/';

			echo '<span style="color:blue">';
			echo '*'.(string)$Item.'*';
			echo '</span>';


			if(preg_match($regExp, trim((string)$Item))){
				echo ' <b style="color:green">Valid</b>'.$regExp;
				echo $result;
				$result=true;
			}else{
				echo ' <b style="color:red">NOT Valid</b>'.$regExp;
				echo $result;
				$result=false;
			}
			echo '<br>';
			if ($result==true){
				$result=$Item->validate($level, $newParent);
			}else{
				return $result;
			}
		}
		return $result;
	}

}

?>
