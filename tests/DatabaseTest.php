<?php 
use alen\Database;

class DatabaseTest extends PHPUnit_Framework_TestCase {
	
	public function testDatabaseConstructorTrue() {
		$db = new Database();
		
		$this->assertNotNull($db);
	}
	
	public function testInsertSuccessfulIntoDatabase() {
		$db = new Database();
		
		$result = $db->insertData("test");
		
		$this->assertTrue($result);
	}
}


?>