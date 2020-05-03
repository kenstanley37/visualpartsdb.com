<?php
/**
* Controls the database access for the entire site
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/

/**
 * Database handles the database connection methods
 *
 * @author Ken Stanley <ken@stanleysoft.org>
 * @license MIT
 */
namespace db;

use PDO;

class db {

    public function __construct(){
        $config = include(__DIR__.'../../../config.php');

        $this->dbhost = $config['dbhost'] ?? 'default value';
        $this->dbuser = $config['dbuser'] ?? 'default value';
        $this->dbpass = $config['dbpassword'] ?? 'default value';
        $this->dbname = $config['dbname'] ?? 'default value';
    }

    public function dbConnect(){   
        
        $this->conn = null;    
        
        try
		{
            $this->conn = new \PDO("mysql:host=" . $this->dbhost. ";dbname=" . $this->dbname, $this->dbuser, $this->dbpass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

        }
		catch(PDOException $e)
		{
            throw new pdoDbException($e);
            return "Connection error: " . $e->getMessage();
        }
        
        return $this->conn;
    }
}


?>