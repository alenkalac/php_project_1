<?php 
use alen\Database;
use alen\Student;

class DatabaseTest extends \PHPUnit_Framework_TestCase {
	
	public function testDatabaseConstructorTrue() {
		$db = new Database();
		
		$this->assertNotNull($db);
	}
	
	public function testGettingStudentFirstName() {
		$student = new Student(999888);
		
		$result = "Alen";
		
		$this->assertEquals($student->getName(), $result);
	}
}

?>