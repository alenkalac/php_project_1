<?php
namespace alen;

/**
 * @author Alen Kalac
 *
 */
class Student {
	
	private $id;
	private $name;
	private $surname;
	private $barcode;
	private $dob;
	private $attendance;
	private $rank;
	private $belt;
	private $technique;
	
	
	public function __construct($details) {
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
	 * @codeCoverageIgnore
	 */
	public function getStudentFromDB($barcode) {
		$database = new Database();
		$s = $database->getStudentByBarcode($barcode);
		
		$this->id = $s['id'];
		$this->name = $s['name'];
		$this->surname = $s['surname'];
		$this->barcode = $s['barcode'];
		//$this->rank = $s['rank'];
		$this->belt = $s['belt'];
		$this->dob = $s['dob'];
		$this->attendance = $database->getAttendance($this->barcode);
		$this->technique = $database->getTechniqueForBelt($this->rank);
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setSurname($surname) {
		$this->surname = $surname;
	}
	
	public function getSurname() {
		return $this->surname;
	}
	
	public function setBarcode($barcode) {
		$this->barcode = $barcode;
	}
	
	public function getBarcode() {
		return $this->barcode;
	}
	
	public function setAttendance($attendance) {
		$this->attendance = $attendance;
	}
	
	public function getAttendance() {
		return $this->attendance;
	}
	
	public function setTechnique($tech) {
		$this->technique = $tech;
	}
	
	public function getTechnique() {
		return $this->technique;
	}
	
	public function setBelt($belt) {
		$this->belt = $belt;
	}
	
	public function getBelt() {
		return $this->belt;
	}
	
	public function setRank($rank) {
		$this->rank = $rank;
	}
	
	public function getRank() {
		return $this->rank;
	}
	
	public function setDob($dob)  {
		$this->dob = $dob;
	}
	
	public function getDob() {
		return $this->dob;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public static function getStudentById($id) {
		$database = new Database();
		$s = $database->getStudentById($id);
		
		return new Student($s['barcode']);
	}
}