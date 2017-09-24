<?php
class database
{
	private $_ip = "localhost";
	private $_username = "merchant2";
	private $_password = "CentreVille9";
	private $_database_name = "merchant2";
	
	private $_database = null; 
	
	private $_insert_id = null;
	
	
	
	/*
	**	Setup the connection with the Merchant database.
	**	Store the connection inside a class variable
	**	for quick access by the functions.
	*/
	
	public function __construct()
	{
		$this->_database = mysqli_connect($this->_ip, $this->_username, $this->_password, $this->_database_name);
		
		if(mysqli_connect_errno()) 
		{
			if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
			{
				die("Database error. No connection could be established.");
			}
			else
			{
				$this->_throwUserError();
			}
		}
	}
	
	
	
	/*
	**	The query function is used to run a query
	**	on the Merchant database. Insert ID's are
	**	stored for later use.
	*/
	
	public function query($query)
	{
		if($result = mysqli_query($this->_database, $query))
		{
			$this->_insert_id = mysqli_insert_id($this->_database);
			return $result;
		}
		else
		{
			if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
			{
				die("Query error in database script. The following query is not functional: <pre>" . $query . "</pre><br/><br/><br/>" . mysqli_error($this->_database));
			}
			else
			{
				$this->_throwUserError();
			}
		}
		
		return false;
	}
	
	
	
	/*
	**	Fetch functions, both assoc and array. Used for
	**	returning multiple database rows. The array function
	**	makes the return a standard PHP array used by FOREACH.
	*/
	
	public function fetch_assoc($result)
	{
		return mysqli_fetch_assoc($result);
	}
	
	public function fetch_array($result)
	{
		return $result;
		$return = array();
		
		while($row = $this->fetchAssoc($result))
		{
			$return[] = $row;
		}
		
		return $return;
	}
	
	
	
	/*
	**	Returns the number of rows after executing
	**	a query with the query function.
	*/
	
	public function num_rows($result)
	{
		return mysqli_num_rows($result);
	}
	
	
	
	/*
	**	In order to keep the database input save, all text
	**	inputs must be validated with the escape_string
	**	function. All harmfull tags are stripped.
	*/
	
	public function real_escape_string($string)
	{
		return mysqli_real_escape_string($this->_database, $string);
	}
	
	
	
	/*
	**	After an insert, the insert ID variable is fulled
	**	by the query. This functions returns the value.
	*/
	
	public function insert_id()
	{
		return $this->_insert_id;
	}
	
	
	
	/*
	**	When inserting a FLOAT variable, users may use
	**	the wrong seperators. This function changes that
	**	to a dot (.), which the database will accept.
	*/
	
	public function floatvalue($float)
	{
		return str_replace(",", ".", $float);
	}
	
	
	
	/*
	**	When inserting a DATE variable, users may use
	**	the wrong sequence for the database to understand.
	**	This function transforms it into a MySQL date.
	*/
	
	public function datevalue($date)
	{
		$d = substr($date, 0, 2);
		$m = substr($date, 3, 2);
		$y = substr($date, 6, 4);
		
		return $y . "-" . $m . "-" . $d;
	}
}
?>