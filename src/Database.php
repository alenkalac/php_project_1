<?php
namespace alen;

use \PDO;

/**
 * @codeCoverageIgnore
 */
class Database {

	private $database = '';
	private $username = 'alen';
	private $password = 'helloworld';
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

		if(password_verify($password, $passDatabase)) {
			$user = new User($username, $id, $role);
			return $user;
		}else {
			return null;
		}
	}

	public function getAllStudents() {
		$query = $this->databaseConnection->prepare("SELECT * FROM student LEFT JOIN belts on student.belt = belts.id ");
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
		$query = $this->databaseConnection->prepare("SELECT * FROM student JOIN belts ON student.belt = belts.id WHERE barcode = :BARCODE");
		$query->bindParam(":BARCODE", $barcode);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	public function getAttendance($barcode){
		$query = $this->databaseConnection->prepare("SELECT * FROM attendance WHERE student_barcode = :BARCODE ORDER BY id DESC");
		$query->bindParam(":BARCODE", $barcode);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function getTechniqueForBelt($belt) {
		$query = $this->databaseConnection->prepare("SELECT * FROM techniques WHERE belt = :BELT");
		$query->bindParam(":BELT", $belt);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function registerStudentAttendance($barcode) {
		$dt = new \DateTime();
		$timestamp = $dt->getTimestamp();

		//check if student exists
		$student = new Student([]);
		$student->getStudentFromDB($barcode);
		
		if(!$student->getBarcode())
			return 0;

		$query = $this->databaseConnection->prepare("INSERT INTO attendance VALUES(null, :BARCODE, :TIMESTAMP)");
		$query->bindParam(":BARCODE", $barcode);
		$query->bindParam(":TIMESTAMP", $timestamp);
		if($query->execute())
			return 1;
		return 0;
	}
	
	public function getAllBelts() {
		$query = $this->databaseConnection->prepare("SELECT * FROM belts");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
	}

}
