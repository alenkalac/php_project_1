<?php

use alen\Student;

class StudentTest extends PHPUnit_Framework_TestCase {
	
	public function testStudentConstructorNotNull() {
		
		$student = new Student ( [ ] );
		
		$this->assertNotNull ( $student );
	}
	
	public function testSettingStudentIdViaConstructor() {
	
		$options = [
				'id' => 10,
		];
	
		$student = new Student ( $options );
	
		$this->assertEquals($options['id'], $student->getId());
	}
	
	public function testSettingStudentNameViaConstructor() {
		
		$options = [
				'name' => 'student1',
		];
		
		$student = new Student ( $options );
		
		$this->assertEquals($options['name'], $student->getName());
	}
	
	public function testSettingStudentSurnameViaConstructor() {
	
		$options = [
				'surname' => 'student_last',
		];
	
		$student = new Student ( $options );
	
		$this->assertEquals($options['surname'], $student->getSurname());
	}
	
	public function testSettingStudentBarcodeViaConstructor() {
	
		$options = [
				'barcode' => '999888',
		];
	
		$student = new Student ( $options );
	
		$this->assertEquals($options['barcode'], $student->getBarcode() );
	}
	
	public function testSettingStudentBeltViaConstructor() {
		$options = [
				'belt' => 'White',
		];
		
		$student = new Student ( $options );
		
		$this->assertEquals( $options['belt'], $student->getBelt());
	}
	
	public function testSettingStudentDobViaConstructor() {
		$options = [
				'dob' => '12-12-1987',
		];
		
		$student = new Student ( $options );
		
		$this->assertEquals($options['dob'], $student->getDob());
	}

	public function testSettingStudentAttendance() {
		$student = new Student([]);
		
		$student->setAttendance("01");
		
		$expected = "01";
		
		$this->assertEquals($expected, $student->getAttendance());
		
	}
	
	public function testSettingStudentTechnieque() {
		$student = new Student([]);
	
		$student->setTechnique("Punch");
	
		$result = "Punch";
	
		$this->assertEquals($result, $student->getTechnique());
	
	}
	
	public function testSettingStudentRank() {
		$student = new Student([]);
	
		$student->setRank("11");
	
		$result = "11";
	
		$this->assertEquals($result, $student->getRank());
	
	}
	
}