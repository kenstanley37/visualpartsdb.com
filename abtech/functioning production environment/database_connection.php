<?php
class Database
{   
    private $host = "localhost";
    private $db_name = "stanle14_vpd";
    private $username = "stanle14_vpd";
    private $password = "*#bOJJ@G7Kh?";
    public $conn;
     
    // ******************************
    // Database connection using PDO
    // ******************************
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
?>