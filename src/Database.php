<?php
namespace alen;

use \PDO;

class Database {
	
	private $database = '';
	private $username = 'root';
	private $password = '';
	private $databaseConnection;
	
	public function __construct() {
		try {
			$this->databaseConnection = new PDO("mysql:host=localhost:3306;dbname=php_project_1", $this->username, $this->password);
			$this->databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return true;
		} catch(\PDOException $e) {
			return false;
		}
	}
	
	public function insertData($data) {
		$query = $this->databaseConnection->prepare("INSERT INTO data (data) VALUES (:DATA)");
		$query->bindParam(":DATA", $data);
		$query->execute();
		
		if($query->rowCount() > 0)
			return true;
		else return false;
	}
	
}


