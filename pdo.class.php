<?php  
/**
 * 
 */
class DB
{
	private $host = DB_HOST;  
	private $user = DB_USER;  
	private $pass = DB_PASS;  
	private $dbname = DB_NAME;
	private $stmt;

	private $dbh;  
	private $error;
	
	public function __construct(){  
		// Set DSN  
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;  
		// Set options  
		$options = array(  
			PDO::ATTR_PERSISTENT => true,  
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  
		);  
		// Create a new PDO instanace  
		try{  
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);  
		}  
		// Catch any errors  
		catch(PDOException $e){  
			$this->error = $e->getMessage();  
		}  
	} 

	/*
	The query method introduces the $stmt variable to hold the statement.

	The query method also introduces the PDO::prepare function.

	The prepare function allows you to bind values into your SQL statements. This is important because it takes away the threat of SQL Injection because you are no longer having to manually include the parameters into the query string.

	Using the prepare function will also improve performance when running the same query with different parameters multiple times.
	*/
	public function query($query){  
		$this->stmt = $this->dbh->prepare($query);  
	}  

	/*
	The next method we will be looking at is the bind method. In order to prepare our SQL queries, we need to bind the inputs with the placeholders we put in place. This is what the Bind method is used for.
	The main part of this method is based upon the PDOStatement::bindValue PDO method.
	Firstly, we create our bind method and pass it three arguments.

	Param is the placeholder value that we will be using in our SQL statement, example :name.

	Value is the actual value that we want to bind to the placeholder, example “John Smith”.

	Type is the datatype of the parameter, example string.

	Next we use a switch statement to set the datatype of the parameter:
	*/
	public function bind($param, $value, $type = null){
		if (is_null($type)) {  
			switch (true) {  
				case is_int($value):  
					$type = PDO::PARAM_INT;  
					break;  

				case is_bool($value):  
					$type = PDO::PARAM_BOOL;  
					break;  

				case is_null($value):  
					$type = PDO::PARAM_NULL;  
					break;  

				default:  
				$type = PDO::PARAM_STR;  
			}  
		}
		$this->stmt->bindValue($param, $value, $type);   
	}

	/*
	The execute method executes the prepared statement.
	*/
	public function execute(){  
		return $this->stmt->execute();  
	} 

	/*
	The Result Set function returns an array of the result set rows. It uses the PDOStatement::fetchAll PDO method. First we run the execute method, then we return the results.
	*/
	public function resultset(){  
		$this->execute();  
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);  
	} 

	/*
	Very similar to the previous method, the Single method simply returns a single record from the database. Again, first we run the execute method, then we return the single result. This method uses the PDO method PDOStatement::fetch.
	*/
	public function single(){  
		$this->execute();  
		return $this->stmt->fetch(PDO::FETCH_ASSOC);  
	}  

	/*
	The next method simply returns the number of effected rows from the previous delete, update or insert statement. This method use the PDO method PDOStatement::rowCount.
	*/
	public function rowCount(){  
		return $this->stmt->rowCount();  
	} 

	/*
	The Last Insert Id method returns the last inserted Id as a string. This method uses the PDO method PDO::lastInsertId.
	*/
	public function lastInsertId(){  
		return $this->dbh->lastInsertId();  
	}  

	public function beginTransaction(){  
		return $this->dbh->beginTransaction();  
	}

	public function endTransaction(){  
		return $this->dbh->commit();  
	}   

	public function cancelTransaction(){  
		return $this->dbh->rollBack();  
	} 

	public function debugDumpParams(){  
		return $this->stmt->debugDumpParams();  
	}
}
?>
