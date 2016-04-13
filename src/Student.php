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
	
	public function __construct($barcode) {
		
		$database = new Database();
		$s = $database->getStudentByBarcode($barcode);
		
		$this->id = $s['id'];
		$this->name = $s['name'];
		$this->surname = $s['surname'];
		$this->barcode = $s['barcode'];
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
	
	
}