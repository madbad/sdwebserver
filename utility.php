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
	public function validate($level=0) {
		global $dataInfo;

		$result=true;
		$level+=1;

		foreach($this->children() as $item){
			if($level>1){

				//get the index of the validator
				$index=$item->attributes()->validateAs;

				//create the regExp from the dataInfo array
				$regExp= '/'.$dataInfo["$index"][1].'/';

				//test the regExp
				$result=preg_match($regExp, trim((string)$item));

				//echo str_repeat ( '-' , $level );
				//echo (string)$item->getName().':'.$result.'<br>';
			}

			if ($result==true){
				$result=$item->validate($level);
			}else{
				return false;
			}
		}
		return $result;
	}
	public function getDataArray($level=-1, $prefix=null, &$array=null, $neededDataType='value') {
		global $dataInfo;

		$level+=1;

		$newName='';


		if ($prefix!=''){
			$prefix.='_';
		}


		foreach($this->children() as $elementName => $child){

			//check if this element has a list of this child
			if($this->hasListOf($elementName)){
				$myArray=&$array[];
			}else{
				$myArray=&$array;
			}

			//the name of the child in "parent_child" form
			$newName=$prefix.$elementName;

			if($level>0){//we want to skip the first level as it has no real data but is only a container
				//save the needed data
				if($neededDataType=='value'){
					//save the value of the child
					$myArray[$newName]=trim((string)$child);
				}else if($neededDataType=='dbType'){
					//save the database type
					$index=$child->attributes()->validateAs;
					$myArray[$newName]= $dataInfo["$index"][0];
				}else{
					die ('Unkow data type to return on xml->getDataArray call');
				}
			}

			//go on with the next xml object level using the "local" array
			$child->getDataArray($level, $newName, $myArray, $neededDataType);

			//switch back to the "master" array
			//for the next element in the current level
			$myArray=&$array;
		}

		return $array;
	}

	public function saveToDb(){
		global $myDb;
		$dataRows=$this->getDataArray();
		$table=$this->getDbTable();

		//if the table does not exist create it with the needed columns
		if(!$myDb->hasTable($table)){
			$columns=$this->getDbStructure();
			$myDb->createTable($table, $columns);
		};

		//actually save the data into the table
		foreach ($dataRows as $data){
			$myDb->insert($data,$table);
		}
	}

	public function getDbStructure($level=-1, $parent=null, &$array=null){
		//we return the first array even if there are 2 or more because the structure should be the same /*not realy real*/  /*to fix ??*/
		return $this->getDataArray($level, $parent, $array, $neededDataType='dbType')[0];
	}

	public function getDbTable(){
		//the name should be as the name of the current element plus a final "_list"
		return $this->children()->getName().'_list';
	}

	public function hasListOf($elementName){
		$namesArray=array();
		foreach($this->children() as $elementName => $child){
			if(in_array($elementName,$namesArray)){
				return true;
			}
			$namesArray[]=$elementName;
		}
		return false;
	}
}
function dump($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}
?>
