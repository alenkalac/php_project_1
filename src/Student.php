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
	private $attendance;
	
	public function __construct($barcode) {
		
		$database = new Database();
		$s = $database->getStudentByBarcode($barcode);
		
		$this->id = $s['id'];
		$this->name = $s['name'];
		$this->surname = $s['surname'];
		$this->barcode = $s['barcode'];
		
		$this->attendance = $database->getAttendance($this->barcode);
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
	
	public static function getStudentById($id) {
		$database = new Database();
		$s = $database->getStudentById($id);
		
		return new Student($s['barcode']);
	}
	
	
}