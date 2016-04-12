<?php

namespace alen;

class User {
	private $username = '';
	private $id = '';
	private $role = '';
	
	public function __construct($username, $id, $role) {
		$this->username = $username;
		$this->id = $id;
		$this->role = $role;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getRole() {
		return $this->role;
	}
}

?>

