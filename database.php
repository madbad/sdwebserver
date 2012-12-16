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
		$this->connect();
	}

    public function connect() {

		//connect to the host
		$link = mysql_connect($this->host, $this->username, $this->password)
			or die('Could not connect to the host: ' . mysql_error());

		//select the database
		mysql_select_db($this->name) 
			or die('Could not select the database: ' . mysql_error());

	}

	public function query($query){
		echo '<br>'.$query;
		return mysql_query($query)
			or die('Could not execute the query: ' . mysql_error());  
	}

	public function insert($inserts, $table){
		/*
			$inserts is an array with key value pairs where $key=$fieldname $value=$fieldvalue
			$table is the table name 
		*/

		//remove key from the list
		unset($inserts['host_id']);


		//
		$values = array_map('mysql_real_escape_string', array_values($inserts));
		$keys = array_keys($inserts);
		$query='INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';
		return $this->query($query);
	}

	public function update($updates, $table){
		/*
			$inserts is an array with key value pairs where $key=$fieldname $value=$fieldvalue
			$table is the table name 
		*/
		$values = array_map('mysql_real_escape_string', array_values($updates));
		$keys = array_keys($updates);
		$query="UPDATE Persons SET Age=36 WHERE FirstName='Peter' AND LastName='Griffin'";
		return $this->query($query);
	}

	public function remove(){
		$query='';
		return $this->query($query);
	}

	public function hasTable($table){
		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))==1){
			return true;
		}else{
			return false;
		}
	}

	public function createTable($table, $columns){
		$query ="CREATE TABLE $table (";
		foreach ($columns as $name=> $type){
			if(preg_match('/(_id)$/', $name)){
				$key=$name;
				$query.="$name INT NOT NULL AUTO_INCREMENT,";
			}else{
				$query.="$name $type,";
			}
		}
		$query.="PRIMARY KEY ($key))";
		//$query=substr($query,0,-1).')';
		$this->query($query);
	}
}
?>
