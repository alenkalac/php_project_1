<?php
/**
 * Student Class
 */
namespace alen;

/**
 * The Student class that represents a student
 * @author Alen Kalac
 */
class Student {
	
	/**
	 * Student's ID
	 * @var int
	 */
	private $id;
	
	/**
	 * Student's Name
	 * @var string
	 */
	private $name;
	
	/**
	 * Student's Surname
	 * @var string
	 */
	private $surname;
	
	/**
	 * Student's Barcode that they are registered with
	 * @var int|string
	 */
	private $barcode;
	
	/**
	 * Student's Date of birth
	 * @var string
	 */
	private $dob;
	
	/**
	 * Student's Attendance
	 * @var array
	 */
	private $attendance;
	
	/**
	 * Student's Rank
	 * @deprecated
	 * @var int
	 */
	private $rank;
	
	/**
	 * Student's Current Belt aquired
	 * @var int
	 */
	private $belt;
	
	/**
	 * List of techinieqs for the next belt
	 * @var array
	 */
	private $technique;
	
	/**
	 * Detault Constructor for the Student Class
	 * @param array $details
	 */
	public function __construct($details = []) {
		if(isset($details['id']))
			$this->setId($details['id']);
		if(isset($details['name']))
			$this->setName($details['name']);
		if(isset($details['surname']))
			$this->setSurname($details['surname']);
		if(isset($details['barcode']))
			$this->setBarcode($details['barcode']);
		if(isset($details['rank']))
			$this->setRank($details['rank']);
		if(isset($details['belt']))
			$this->setBelt($details['belt']);
		if(isset($details['dob']))
			$this->setDob($details['dob']);
	}
	
	/**
	 * Returns Student details from the database
	 * @param int|string $barcode
	 */
	public function getStudentFromDB($barcode) {
		$database = new Database();
		$s = $database->getStudentByBarcode($barcode);
		
		$this->id = $s['id'];
		$this->name = $s['name'];
		$this->surname = $s['surname'];
		$this->barcode = $s['barcode'];
		$this->belt = $s['belt'];
		$this->dob = $s['dob'];
		
		$this->attendance = $database->getAttendance($this->barcode);
		$this->technique = $database->getTechniqueForBelt($this->belt);
	}
	
	/**
	 * Sets the student's ID
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * Gets the student's ID
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the student's Name
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Gets the student's Name
	 * @return string 
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the student's last name
	 * @param string $surname
	 */
	public function setSurname($surname) {
		$this->surname = $surname;
	}
	
	/**
	 * Gets the student's Last Name 
	 */
	public function getSurname() {
		return $this->surname;
	}
	
	/**
	 * Sets the student's Barcode
	 * @param int|string $barcode
	 */
	public function setBarcode($barcode) {
		$this->barcode = $barcode;
	}
	
	/**
	 * Get's the student's barcode
	 * @return int|string
	 */
	public function getBarcode() {
		return $this->barcode;
	}
	
	/**
	 * Sets the student's attendance
	 * @param array $attendance
	 */
	public function setAttendance($attendance) {
		$this->attendance = $attendance;
	}
	
	/**
	 * Gets the student's attendace details
	 * @return array
	 */
	public function getAttendance() {
		return $this->attendance;
	}
	
	/**
	 * Sets the list of techniques for the next grade
	 * @param array $tech
	 */
	public function setTechnique($tech) {
		$this->technique = $tech;
	}
	
	/**
	 * Gets the list of techniques
	 * @return array
	 */
	public function getTechnique() {
		return $this->technique;
	}
	
	/**
	 * Set the belt for the Student
	 * @param int $belt
	 */
	public function setBelt($belt) {
		$this->belt = $belt;
	}
	
	/**
	 * Gets the belt that the student currently owns
	 * @return int
	 */
	public function getBelt() {
		return $this->belt;
	}
	
	public function getBeltColour() {
		$db = new Database();
		return $db->getBeltById($this->getBelt());
	}
	
	/**
	 * Sets the student's Rank
	 * @deprecated
	 * @param int $rank
	 */
	public function setRank($rank) {
		$this->rank = $rank;
	}
	
	/**
	 * Gets the student's Rank 
	 * @deprecated
	 * @return int
	 */
	public function getRank() {
		return $this->rank;
	}
	
	/**
	 * Sets the student's Date of birth
	 * @param string $dob
	 */
	public function setDob($dob)  {
		$this->dob = $dob;
	}
	
	/**
	 * Gets the student's date of birth
	 * @return string
	 */
	public function getDob() {
		return $this->dob;
	}
	
	/**
	 * Updates the database with the current object's values
	 */
	public function update() {
		$database = new Database();
		$database->updateStudent($this);
	}
	
	/**
	 * Insert into database with the current object's values
	 */
	public function create() {
		$database = new Database();
		$this->id = $database->insertStudent($this);
	}
	
	/**
	 * Gets the student by thier ID from the database
	 * @codeCoverageIgnore
	 * @param int $id
	 * @return Student
	 */
	public static function getStudentById($id) {
		$database = new Database();
		$s = $database->getStudentById($id);
		
		return new Student($s);
	}
	
	/**
	 * Deletes the current student stored in this object
	 */
	public function delete() {
		$database = new Database();
		$database->deleteStudent($this->getBarcode());
	}
}