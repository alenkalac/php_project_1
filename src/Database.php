<?php
/**
 * The Database Class that handles all interaction with the database
 */

namespace alen;

use \PDO;

/**
 * Database Class that connects to mysql with PDO
 * @codeCoverageIgnore
 */
class Database {
	
	/**
	 * The database to connect to
	 * @var string
	 */
	private $database = 'php_project_1';
	
	/**
	 * IP address of the mysql server
	 * @var string
	 */
	private $host = 'localhost:3307';
	
	/**
	 * The username to log in with to the mysql server
	 * @var string
	 */
	private $username = 'root';
	
	/**
	 * A password to use to connect to the mysql server
	 * @var string
	 */
	private $password = '';
	
	/**
	 * PDO instance/handler that will contain a connection to the mysql
	 * @var PDO
	 */
	private $databaseConnection;

	/**
	 * Default constructor for the Database Class
	 */
	public function __construct() {
		try {
			if(getenv("TRAVIS")) {
				$this->host = '127.0.0.1';
				$this->username = 'root';
				$this->password = '';
			}
			
			$this->databaseConnection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
			//$this->databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return true;
		} catch(\PDOException $e) {
			return false;
		}
	}

	/**
	 * A function that checks user's login credentials and compares thm
	 * @param string $username
	 * @param string $password
	 */
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
	
	/**
	 * Checks if a user already exists in the database
	 * @param string $username
	 * 				Username to check against the database
	 * @return boolean
	 * 				true if user found and false if user is not found
	 */
	public function checkUserExists($username) {
		$query = $this->databaseConnection->prepare("SELECT * FROM user WHERE username = :USER");
		$query->bindParam(":USER", $username);
		$query->execute();
		
		return $query->rowCount();
	}
	
	
	/**
	 * Inserts a new user  to the database and returns the ID of the user. 
	 * this can be used to create a new student if needed
	 * 
	 * @param string $username
	 * @param string $password
	 * @param int $role
	 * 
	 * @return int
	 */
	public function insertNewUser($username, $password, $role) {
		
		$password_hashed = password_hash($password, PASSWORD_DEFAULT);
		
		$query = $this->databaseConnection->prepare("INSERT INTO user VALUES (NULL, :USERNAME, :PASSWORD, :ROLE)");
		$query->bindParam(":USERNAME", $username);
		$query->bindParam(":PASSWORD", $password_hashed);
		$query->bindParam(":ROLE", $role);
		
		$query->execute();
		
		return $this->databaseConnection->lastInsertId();
	}

	/**
	 * A function that gets all the students from the database
	 * @return array
	 */
	public function getAllStudents() {
		$query = $this->databaseConnection->prepare("SELECT * FROM student LEFT JOIN belts on student.belt = belts.belt_id ");
		$query->execute();

		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * Gets a student by id from the database
	 * @param int $id
	 * @return array
	 */
	public function getStudentById($id) {
		$query = $this->databaseConnection->prepare("SELECT * FROM student WHERE id = :ID");
		$query->bindParam(":ID", $id);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * Gets a student details from database based on the Barcode
	 * @param int|string $barcode
	 * @return array
	 */
	public function getStudentByBarcode($barcode) {
		$query = $this->databaseConnection->prepare("SELECT * FROM student JOIN belts ON student.belt = belts.belt_id WHERE barcode = :BARCODE");
		$query->bindParam(":BARCODE", $barcode);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
	/**
	 * Delete a student with the barcode
	 * @param string $barcode
	 * @return boolean
	 */
	public function deleteStudent($barcode) {
		$query = $this->databaseConnection->prepare("DELETE FROM student WHERE barcode = :BARCODE");
		$query->bindParam(":BARCODE", $barcode);
		
		return $query->execute();
	}
	
	/**
	 * Delete a user with the id 
	 * @param int $id
	 * @return boolean
	 */
	public function deleteUser($id) {
		$query = $this->databaseConnection->prepare("DELETE FROM user WHERE id = :ID");
		$query->bindParam(":ID", $id);
	
		return $query->execute();
	}

	/**
	 * Get all the attendance for the student with the barcode
	 * @param int|string $barcode
	 * @return array
	 */
	public function getAttendance($barcode){
		$query = $this->databaseConnection->prepare("SELECT * FROM attendance WHERE student_barcode = :BARCODE ORDER BY id DESC");
		$query->bindParam(":BARCODE", $barcode);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * gets all the techniques that are part of the belt group
	 * @param int $belt
	 * @return array
	 */
	public function getTechniqueForBelt($belt) {
		$query = $this->databaseConnection->prepare("SELECT * FROM techniques WHERE belt = :BELT");
		$query->bindParam(":BELT", $belt);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * Marks a student as attended for that day based on barcode
	 * @param int|string $barcode
	 * @return boolean
	 */
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
		return $query->execute();
	}
	
	/**
	 * Gets all belts from the database
	 * @return array
	 */
	public function getAllBelts() {
		$query = $this->databaseConnection->prepare("SELECT * FROM belts");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
	/**
	 * Gets all belts from the database
	 * @return String Colour of the belt
	 */
	public function getBeltById($id) {
		$query = $this->databaseConnection->prepare("SELECT * FROM belts WHERE belt_id = :ID");
		$query->bindParam(":ID", $id);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
	
		return $result['belt_color'];
	}
	
	/**
	 * Updates student details
	 * @param Student $student
	 * @return boolean
	 */
	public function updateStudent(Student $student) {
		
		$query = $this->databaseConnection->prepare("UPDATE `student` SET `barcode` = :BARCODE, `name` = :NAME, `surname` = :SNAME, `dob` = :DOB, `belt` = :BELT WHERE `id` = :ID");
		
		$id =  $student->getId();
		$barcode = $student->getBarcode();
		$name = $student->getName();
		$sname = $student->getSurname();
		$dob = $student->getDob();
		$belt = $student->getBelt();
		
		$query->bindParam(":ID", $id);
		$query->bindParam(":BARCODE", $barcode);
		$query->bindParam(":NAME", $name);
		$query->bindParam(":SNAME", $sname);
		$query->bindParam(":DOB", $dob);
		$query->bindParam(":BELT", $belt);
		
		return $query->execute();
	}
	
	/**
	 * Insert student with details
	 * @param Student $student
	 * @return boolean
	 */
	public function insertStudent(Student $student) {
	
		$query = $this->databaseConnection->prepare("INSERT INTO student VALUES(:ID, :BARCODE, :NAME, :SNAME, :DOB, :BELT)");
	
		$id =  $student->getId();
		$barcode = $student->getBarcode();
		$name = $student->getName();
		$sname = $student->getSurname();
		$dob = $student->getDob();
		$belt = $student->getBelt();
	
		$query->bindParam(":ID", $id);
		$query->bindParam(":BARCODE", $barcode);
		$query->bindParam(":NAME", $name);
		$query->bindParam(":SNAME", $sname);
		$query->bindParam(":DOB", $dob);
		$query->bindParam(":BELT", $belt);
	
		$query->execute();
		
		return $this->databaseConnection->lastInsertId();
	}

}
