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
	
	public function __construct($barcode) {
		
		$database = new Database();
		$s = $database->getStudentByBarcode($barcode);
		
		$this->id = $s['id'];
		$this->name = $s['name'];
		$this->surname = $s['surname'];
		$this->barcode = $s['barcode'];
		$this->rank = $s['belt'];
		$this->belt = $s['belt_color'];
		$this->dob = $s['dob'];
		$this->attendance = $database->getAttendance($this->barcode);
		$this->technique = $database->getTechniqueForBelt($this->rank);
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getSurname() {
		return $this->surname;
	}
	
	public function getBarcode() {
		return $this->barcode;
	}
	
	public function getAttendance() {
		return $this->attendance;
	}
	
	public function getTechnique() {
		return $this->technique;
	}
	
	public function getBelt() {
		return $this->belt;
	}
	
	public function getRank() {
		return $this->rank;
	}
	
	public function getDob() {
		return $this->dob;
	}
	
	public static function getStudentById($id) {
		$database = new Database();
		$s = $database->getStudentById($id);
		
		return new Student($s['barcode']);
	}
}