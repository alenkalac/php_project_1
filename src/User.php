<?php
/**
 * User Class
 */
namespace alen;

/**
 * User Class
 * @author Alen Kalac
 */
class User {
	
	/**
	 * User's Name
	 * @var string $username
	 */
	private $username = '';
	
	/**
	 * Id of the user
	 * @var int $id
	 */
	private $id = '';
	
	/**
	 * User's Role, 0 for student, 1 for admin
	 * @var int $role
	 */
	private $role = '';
	
	/**
	 * Class Constructor
	 * @param string $username
	 * @param string|int $id
	 * @param string|int $role
	 */
	public function __construct($username, $id, $role) {
		$this->username = $username;
		$this->id = $id;
		$this->role = $role;
	}
	
	/**
	 * get's the user's username. this is the username they log in with
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * get's the user's Id
	 * @return string|int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * get's the user's Role.
	 * @return string|int
	 */
	public function getRole() {
		return $this->role;
	}
	
	/**
	 * unserializes data that has been serialized by php so it can be stored in a session
	 * @param string $data
	 * @return User
	 * @codeCoverageIgnore
	 */
	public static function fromSerialize($data) {
		return unserialize($data);
	}
}

?>

