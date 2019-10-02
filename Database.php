<?php
if(file_exists('config.php'))
{
	include 'config.php';
} else {
	header('Location: install/index.php');
	exit();
}

/*
NOTE: config.php should contain values for $hostname, $username, $password, and $db_name

Database class
	function __construct()
	creates a database connection object

	function query($sql)
	performs a query with SQL statement and any parameters, and returns a result set
	
	function get_row($result=NULL)
	gets a row.  if the $result parameter is not included, it'll return a row from the last query executed, otherwise, it'll use the query from the $result set
	
	function next_row($result=NULL)
	fetches the next row of a multi-result query.  if the $result parameter is not included, it'll return a row from the last query executed, otherwise, it'll use the query from the $result set
	
	function how_many($result=NULL)
	returns the total number of results in a result set.  if the $result parameter is not included, it'll return a row from the last query executed, otherwise, it'll use the query from the $result set
	
	function error($string, $statement=NULL)
	prints out an error message, contained in $string, and stops execution.  if the $statement parameter is included, it will output that out as the SQL that caused the error
	
function make_safe($unSafe)
sanitizes user input so that it's safe to store in the database.  failure to use this function could result in loss of data, or hacking, and could compromise the security of the site
*/

class Database
{
	var $database;
	var $result;
	function __construct()
	{
		global $hostname, $username, $password, $db_name;
		$this->database = new mysqli($hostname, $username, $password, $db_name);
		
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	}
	
	function query($sql)
	{
		$args = func_get_args();
		$sql = array_shift($args);
		//if(isset($this->result))
		//	$this->result->close();
		
		if(substr_count($sql, '?')!=count($args))
		{
			$this->error("Mismatch in parameter totals.  Expecting ".substr_count($sql, '?')." recieved ".count($args));
		}
		
		//rebuild the sql statement with the arguements included, safely
		$splitsql=explode('?', $sql);
		$sql=$splitsql[0];

		for($i=0; $i<count($args); $i++)
		{
			$sql.=$this->database->real_escape_string($args[$i]) . $splitsql[$i+1];
		}
		
		//echo $sql;
		
		if(!($this->result = $this->database->query($sql)))
		{
			$this->error("Executing database query", $sql);
		}

		return $this->result;
	}
	
	function get_row($result=NULL)
	{
		//$this->query($sql);
		if($result==NULL)
			return $this->result->fetch_array();
		else
			return $result->fetch_array();
	}
	
	/*function get_row()
	{
		return $result->fetch_array();
	}*/
	function next_row($result=NULL)
	{
		if($result==NULL)
			return $this->result->next_result();
		else
			return $result->next_result();
	}
	
	function how_many($result=NULL)
	{
		if($result==NULL)
			return $this->result->num_rows;
		else
			return $result->num_rows;
	}
	
	function error($string, $statement=NULL)
	{
		if($statement)
			print("SQL: $statement <br/>");
		printf("Error: %s || %s", $string, $this->database->error);
		die();
	}
	
	function __destruct()
	{
		//if(isset($this->result))
		//	$this->result->close();
		$this->database->close();
	}
}

function make_safe($unSafe)
{
	$safe=strip_tags($unSafe);
	$safe=stripslashes($safe);
	$safe=htmlspecialchars($safe, ENT_NOQUOTES);
	return $safe;
}
?>