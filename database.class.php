<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

class DataBase
{
	function DataBase(){
		$this->name='speed__dreams_fr_servers';
	    $this->host='localhost';
		$this->username='root';
		$this->password='faggod50';
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

	public function query(){
		$result = mysql_query("SELECT * FROM test")
			or die('Could not execute the query:  ' . mysql_error());  
	}
/*
<?php
function mysql_insert($table, $inserts) {
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);
       
    return mysql_query('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')');
}
?>
*/




/*

<?php

 * If `ve made a fast way to
 * get a sql statement out of a array
 * Just give an array to the function with
 *  your Keys (sql) and the
 * values in the other array
 *
 * array('keys'=>array('key','key2','key3'),
           'values'=>array(
            array('test','test2','test3'),
            array('best','best2','best3'),
            array('nest','nest2','nest3'),
             )
           );
 *
 * returns:
 * insert:  "(`key`,`key2`,`key3`)VALUES ('test','test2','test3'), ('best','best2','best3'), ('nest','nest2','nest3')"
 * Update:  "SET `key`='test', `key2`='test2', `key3`='test3'"
 *
 *


$params=array('keys'=>array('key','key2','key3'),
           'values'=>array(
            array('test','test2','test3'),
            array('best','best2','best3'),
            array('nest','nest2','nest3'),
             )
           );

echo array2Sql($params,'insert');

function array2Sql($params=array(),$mod='insert')
{

#make it uniform (for insert, if only an pure array given)
if(!is_array($params['values']['0'])){
     $new['values']['0']=$params['values'];
     $params['values']= $new['values'];
}

switch($mod){

     case'insert':
      $vals=array();
      $keys='(`'.join('`,`',$params['keys']).'`)';

      foreach($params['values'] as $k=>$v){

             $vals[]='(\''.join('\', \'',$v).'\')';

      }
           $vals=implode(',', $vals);

      return $sql=$keys.'VALUES '.$vals;

     case'update':
      $sets=array();
      $i=0;
      foreach($params['values']['0'] as $k=>$v){
          $sets[]='`'.$params['keys'][$i].'`=\''.$v.'\'';
           $i++;
      }
       return $sql='SET '.implode(', ',$sets);

     }
     return false;

}
?>


*/

}
$myDb=new DataBase;
$myDb->query();

?>
