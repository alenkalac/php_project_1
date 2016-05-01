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
	 * Creates a user and stores it in a database
	 * @param string $username
	 * 				Username of the user, used with login page
	 * @param string $password
	 * 				Unhashed password
	 * @param int $role
	 * 				Role, 1 is admin, 2 is student
	 */
	public static function createUser($username, $password, $role) {
		$database = new Database();
		$id = $database->insertNewUser($username, $password, $role);
		
		return new User($username, $id, $role);
	}
	
	public function delete() {
		$database = new Database();
		$database->deleteUser($this->getId());
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

