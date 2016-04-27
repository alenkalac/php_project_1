<?php
use alen\User;
use alen\Roles;

class UserTest extends PHPUnit_Framework_TestCase {
	
	public function testUserConstructorNotNull() {
		$user = new User('Admin', '0', Roles::$ADMIN);
		
		$this->assertNotNull($user);
	}
	
	public function testGetUserName() {
		$user = new User('Admin', '0', Roles::$ADMIN);
		
		$expected = 'Admin';
		
		$this->assertEquals($expected, $user->getUsername());
	}
	
	public function testGetUserId() {
		$user = new User('Admin', '0', Roles::$ADMIN);
	
		$expected = '0';
	
		$this->assertEquals($expected, $user->getId());
	}
	
	public function testGetUserRoleAdmin() {
		$user = new User('Admin', '0', Roles::$ADMIN);
	
		$expected = Roles::$ADMIN;
	
		$this->assertEquals($expected, $user->getRole());
	}
	
	public function testGetUserRoleSetStudentTestAdmin() {
		$user = new User('Admin', '0', Roles::$STUDENT);
	
		$expected = Roles::$ADMIN;
	
		$this->assertNotEquals($expected, $user->getRole());
	}
	
}