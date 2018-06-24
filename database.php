<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

class DataBase
{
	function DataBase($params){
		$this->name = $params->name;
	    $this->host = $params->host;
		$this->username = $params->username;
		$this->password = $params->password;
		$this->link='';
		$this->connect();
	}

	public function connect() {
		//connect to the host
		$this->link = mysqli_connect($this->host, $this->username, $this->password)
			or die('Could not connect to the host: ' . mysqli_error());

		//select the database
		mysqli_select_db($this->link, $this->name)
			or die('Could not select the database: ' . mysqli_error());
		return 1;
	}

	public function query($query){
		$result = mysqli_query($this->link, $query)
			or die('Could not execute the query: '.$query.'<br>' . mysqli_error($this->link));
			
		$this->lastInsertId = mysqli_insert_id($this->link);
		
		return $result;
	}

	public function insert($obj, $table){
		foreach($obj as $key => $value) {
			$keys[]=$key;
			$values[]=$value;
		}
		$query='INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';
		return $this->query($query);
	}

	public function update($updates, $table, $conditions){
		$where=$this->generateConditions($conditions);

		$values=$this->generateValues($updates);

		$query="UPDATE $table SET $values WHERE $where";

		return $this->query($query);
	}

	public function select($conditions, $table){

		$where=$this->generateConditions($conditions, ' AND');

		$query='SELECT * FROM '.$table.' WHERE'.$where;
		
		return $this->customSelect($query);
	}

	public function customSelect($query){
		$result=mysqli_query($this->link, $query);
		
		//no result
		if(!$result){return 0;}

		$out=array();
		while ($row = mysqli_fetch_assoc($result)) {
			$out[]=$row;
		}

		//free the mysql result
		mysqli_free_result($result);
		return $out;
	}

	public function selectMin($conditions, $table, $field){

		$where=$this->generateConditions($conditions, ' AND');

		$query='SELECT MIN('.$field.') FROM '.$table.' WHERE'.$where;

		$row=$this->customSelect($query);
		return $row['MIN('.$field.')'];
	}

	public function hasTable($table){
		if(mysqli_num_rows(mysqli_query($this->link, "SHOW TABLES LIKE '".$table."'"))==1){
			return true;
		}else{
			return false;
		}
	}

	public function createTable($table, $columns){
		$query ="CREATE TABLE $table (";

		$tableType=str_replace('_list', '',$table);
		foreach ($columns as $name=> $type){
			if(preg_match('/('.$tableType.'_id)$/', $name)){
				$key=$name;
				$query.="$name INT NOT NULL AUTO_INCREMENT,";
			}else{
				$query.="$name $type,";
			}
		}

		$query.="PRIMARY KEY ($key))";

		$this->query($query);
	}

	public function generateConditions($conditions,$separator =','){
		$txt='';

		foreach ($conditions as $key => $value){
			$txt.=" $key = '$value'".$separator;
		}

		$pos = strrpos($txt, $separator);

		if($pos !== false){
			$txt = substr_replace($txt, '', $pos, strlen($separator));
		}

		return $txt;
	}

	public function generateValues($keyValuesPair){
		$txt='';

		foreach ($keyValuesPair as $key => $value){
			if(!(preg_match( '/^\d*$/' , $value) == 1 )){//se non è un numero
				$delimiter="'";
			}else{
				$delimiter="";
			}

			//
			if ($value==''){
				$delimiter="'";
			}

			$txt.=" $key = ".$delimiter.$value.$delimiter.',';
		}
		//remove the last ","
		$txt = substr($txt, 0, -1);

		return $txt;
	}
}
########################################################

class User
{
	function User($id=''){
		$this->id = $id;
		if($this->id){
			$this->getFromDb();
		}
	}
    public function import($properties){    
		foreach($properties as $key => $value){
			$this->{$key} = $value;
		}
    }
	public function getFromDb(){
		global $myDb;
		$params =  new stdClass;
		$params->id = $this->id;
		$results = $myDb->select($params, 'users');
		if ($results){
			$result = $results[0];
			$this->import($result);
		}else{
			//no valid result found! create a fake user
			$this->username = '<i>guest</i>';
			$this->nation = '';
			
		}
	}
	public function getLink($text='') {
		if($text==''){$text=$this->username;}
		return '<a href="user.php?id='.$this->id.'">'.$text.'</a>';
	}
	public function getSmallFlagImg() {
		return '<img src="./img/flags/flags_small/'.$this->nation.'.png" alt="'.$this->nation.'">';
	}
	public function getMediumFlagImg() {
		return '<img src="./img/flags/flags_medium/'.$this->nation.'.png" alt="'.$this->nation.'">';
	}
	public function getImgFile() {
		return '<img src="'.$this->img.'" alt="'.$this->username.' image">';
	}
}	
########################################################

class Track
{
	function Track($track){
		$this->import($track);
	}
    public function import($properties){    
		foreach($properties as $key => $value){
			$this->{$key} = $value;
		}
    }
	public function getLink($text='') {
		if($text==''){$text=$this->username;}
		return '<a href="track.php?id='.$this->id.'">'.$text.'</a>';
	}
	public function card($text='') {
		return $this->name.$this->img;
	}
	public function imgTag() {
		return "<img width='80' src='".$this->img."' alt='".$this->name."' title='".$this->name."'>";
	}
	public function imgTagFull() {
		return "<img src='".$this->img."'  class='".$this->name."' alt='".$this->name."'>";
	}
	public function clickableName() {
		return $this->linkTag($this->name);

	}
	public function clickableImgTag() {
		return $this->linkTag($this->imgTag());
	}
	public function linkTag($content) {
		return "<a href='./track.php?id=".$this->id."'>".$content."</a>";

	}
}
########################################################

class Car
{
	function Car($car){
		$this->import($car);
	}
    public function import($properties){    
		foreach($properties as $key => $value){
			$this->{$key} = $value;
		}
    }
	public function getLink($text='') {
		if($text==''){$text=$this->username;}
		return '<a href="track.php?id='.$this->id.'">'.$text.'</a>';
	}
	public function card($text='') {
		return $this->name.$this->img;
	}
	public function imgTag() {
//		return "<img width='300' src='".$this->img."'  class='carPreview' alt='car preview'>";
		return "<img width='120' src='".$this->img."' alt='".$this->name."'  title='".$this->name."'>";
	}
	public function imgTagFull() {
		return "<img src='".$this->img."'  class='carPreview' alt='".$this->name."'>";
	}

	public function clickableName() {
		return $this->linkTag($this->name);
	}
	public function clickableImgTag() {
		return $this->linkTag($this->imgTag());
	}
	public function linkTag($content) {
		return "<a href='./car.php?id=".$this->id."'>".$content."</a>";
	}
}
########################################################

class CarCategory
{
	function CarCategory($category){
		$this->import($category);
	}
    public function import($properties){    
		foreach($properties as $key => $value){
			$this->{$key} = $value;
		}
    }
}
########################################################

class TrackCategory
{
	function TrackCategory($category){
		$this->import($category);
	}
    public function import($properties){    
		foreach($properties as $key => $value){
			$this->{$key} = $value;
		}
    }
}



?>
