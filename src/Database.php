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
			$this->databaseConnection = new PDO("mysql:host=localhost:3307;dbname=php_project_1", $this->username, $this->password);
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
	
	public function checkLogin($username, $password) {
		$query = $this->databaseConnection->prepare("SELECT * FROM user WHERE username = :USER");
		$query->bindParam(":USER", $username);
		$query->execute();
		
		$result = $query->fetch();
		
		$id = $result['id'];
		$passDatabase = $result['password'];
		$role = $result['role'];
		
		if(strcmp($password, $passDatabase) === 0)
		{
			$user = new User($username, $id, $role);
			return $user;
		}else {
			return null;
		}
	}
	
	public function getAllStudents() {
		$query = $this->databaseConnection->prepare("SELECT * FROM student");
		$query->execute();
		
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
	public function getStudentById($id) {
		$query = $this->databaseConnection->prepare("SELECT * FROM student WHERE id = :ID");
		$query->bindParam(":ID", $id);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
	public function getStudentByBarcode($barcode) {
		$query = $this->databaseConnection->prepare("SELECT * FROM student WHERE barcode = :BARCODE");
		$query->bindParam(":BARCODE", $barcode);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
}


