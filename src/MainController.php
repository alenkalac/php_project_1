<?php

/**
 * Main Controller for the website
 */
namespace alen;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Main Controller Class
 * @codeCoverageIgnore
 * 
 * @author Alen Kalac
 */
class MainController {
	
	/**
	 * Error page controller
	 * 
	 * @param
	 *        	code
	 *        	An error code
	 * @param Application $app        	
	 */
	public function errorPage($code, Application $app) {
		if (404 === $code) {
			$args = [ 
					'title' => 'ITB Karate | 404 Not Found',
					'page' => 'error' 
			];
			return $app ['twig']->render ( '404.html.twig', $args );
		}
	}
	
	/**
	 * Returns true if session is set to admin
	 * 
	 * @return boolean
	 * 			true if user is an admin
	 */
	private function isAdmin(Application $app) {
		return $app ['session']->get ( 'role' ) == Roles::$ADMIN;
	}
	
	
	/**
	 * Index Page Controller
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function indexPage(Request $request, Application $app) {
		$args = [ 
				'title' => 'ITB Karate | Home Page',
				'page' => 'home' 
		];
		
		return $app ['twig']->render ( 'index.html.twig', $args );
	}
	
	/**
	 * Admin Page Controller
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function adminPage(Request $request, Application $app) {
		$user = User::fromSerialize ( $app ['session']->get ( "user" ) );
		
		if (! $user)
			return new RedirectResponse ( '/' );
		if ($user->getRole () != Roles::$ADMIN)
			return new RedirectResponse ( '/login' );
		
		$database = new Database ();
		$students = $database->getAllStudents ();
		
		$args = [ 
				'name' => $user->getUsername (),
				'title' => 'Admin Page',
				'students' => $students,
				'page' => 'admin' 
		];
		return $app ['twig']->render ( 'admin.html.twig', $args );
	}
	
	/**
	 * Student Page Controller
	 * 
	 * @param String|int $barcode        	
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function studentPage($barcode, Request $request, Application $app) {
		$user = User::fromSerialize ( $app ['session']->get ( "user" ) );
		
		if (! $user)
			return new RedirectResponse ( '/' );
		
		$student = new Student ( [ ] );
		$student->getStudentFromDB ( $barcode );
		
		if (! $student->getId ())
			return new RedirectResponse ( '/' );
		
		$args = [ 
				'name' => $user->getUsername (),
				'title' => 'Student Page ' . $barcode,
				'page' => 'student',
				'user' => $user,
				'student' => $student 
		];
		
		if ($user->getRole () == Roles::$ADMIN)
			return $app ['twig']->render ( 'student.html.twig', $args );
		
		if ($user->getRole () == Roles::$STUDENT)
			if (strcmp ( $app ['session']->get ( 'barcode' ), $barcode ) == 0)
				return $app ['twig']->render ( 'student.html.twig', $args );
		
		return new RedirectResponse ( '/' );
	}
	
	/**
	 * Barcode Page Controller.
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function barcodePage(Request $request, Application $app) {
		$user = User::fromSerialize ( $app ['session']->get ( "user" ) );
		
		$args = [ 
				'title' => 'Barcode Scanner',
				'page' => 'barcode' 
		];
		
		if (! $user)
			return new RedirectResponse ( '/' );
		if (! ($user->getRole () == Roles::$ADMIN))
			return new RedirectResponse ( '/login' );
		
		return $app ['twig']->render ( 'barcode.html.twig', $args );
	}
	
	/**
	 * Logout Page Controller, Handles the logging out and deletes the sessions
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function logoutPage(Request $request, Application $app) {
		$app ['session']->clear ();
		return new RedirectResponse ( '/' );
	}
	
	/**
	 * Login User Post Request Controller
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function postLoginUser(Request $request, Application $app) {
		$database = new Database ();
		
		$username = $request->get ( 'username' );
		$password = $request->get ( 'password' );
		
		$user = $database->checkLogin ( $username, $password );
		
		$args = [ 
				'name' => $username,
				'title' => 'Login Form',
				'page' => 'login' 
		];
		
		if ($user == null) {
			$args ['error'] = "Username or Password are incorrect, Try Again";
			return $app ['twig']->render ( 'login.html.twig', $args );
		}
		
		$userDataSerialized = serialize ( $user );
		
		$app ['session']->set ( "user", $userDataSerialized );
		$app ['session']->set ( "name", $username );
		
		if ($user->getRole () == Roles::$STUDENT) {
			$app ['session']->set ( "role", Roles::$STUDENT );
			$student = Student::getStudentById ( $user->getId () );
			$app ['session']->set ( "barcode", $student->getBarcode () );
			return new RedirectResponse ( "/student/" . $student->getBarcode () );
		} else if ($user->getRole () == Roles::$ADMIN) {
			$app ['session']->set ( "role", Roles::$ADMIN );
			return new RedirectResponse ( "/admin" );
		} else {
			return new RedirectResponse ( "/" );
		}
	}
	
	/**
	 * Login Page Controller to display the Login Page
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function loginPage(Request $request, Application $app) {
		$args = [ 
				'title' => 'Login Form',
				'page' => 'login' 
		];
		
		return $app ['twig']->render ( 'login.html.twig', $args );
	}
	
	/**
	 * Marks a student as addended class using a barcode
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function postRegisterAttendance(Request $request, Application $app) {
		if (!$this->isAdmin($app))
			return - 1;
		
		$barcode = $request->get ( "barcode" );
		
		if (empty ( $barcode ) || strlen ( $barcode ) != 6)
			return - 1;
		
		$db = new Database ();
		$result = $db->registerStudentAttendance ( $barcode );
		
		return $result;
	}
	
	/**
	 * renders an XML page for the calendar to use as data provider.
	 *
	 * @param string|int $barcode        	
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function eventXml($barcode, Request $request, Application $app) {
		$db = new Database ();
		$att = $db->getAttendance ( $barcode );
		
		$args = [ 
				"attendace" => $att 
		];
		
		return $app ['twig']->render ( 'events.xml.twig', $args );
	}
	
	/**
	 * Edit page controller that renders the edit page template
	 * 
	 * @param int|string $barcode        	
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function editPage($barcode, Request $request, Application $app) {
		$student = new Student ( [ ] );
		$student->getStudentFromDB ( $barcode );
		
		$database = new Database ();
		$belts = $database->getAllBelts ();
		
		$args = [ 
				'title' => 'Edit Student',
				'page' => '',
				'student' => $student,
				'belts' => $belts 
		];
		
		return $app ['twig']->render ( 'edit.html.twig', $args );
	}
	
	/**
	 * Post edit Detail controller.
	 * When student's are edited, form posts to this function
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function postEditDetails(Request $request, Application $app) {
		if (!$this->isAdmin($app))
			return new RedirectResponse ( "/login" );
		
		$data = [ 
				'id' => $request->get ( 'id' ),
				'barcode' => $request->get ( 'barcode' ),
				'name' => $request->get ( 'firstname' ),
				'surname' => $request->get ( 'lastname' ),
				'dob' => $request->get ( 'dob' ),
				'belt' => $request->get ( 'belt' ) 
		];
		
		$student = new Student ( $data );
		$student->update ();
		
		return new RedirectResponse ( "/admin" );
	}
	
	/**
	 * Tech page controller that renders the tech page template
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function techPage(Request $request, Application $app) {
		$database = new Database ();
		$belts = $database->getAllBelts ();
		$techs = array ();
		
		foreach ( $belts as $belt ) {
			$techs [$belt ['belt_id']] = $database->getTechniqueForBelt ( $belt ['belt_id'] );
		}
		
		$args = [ 
				'title' => 'Techniques Page',
				'page' => '',
				'belts' => $belts,
				'techs' => $techs 
		]
		;
		
		return $app ['twig']->render ( 'tech.html.twig', $args );
	}
	
	/**
	 * The sign up page for students to register.
	 * 
	 * @param int $choice
	 *        	Choice of the package they chose from the index page
	 * @param Request $request
	 *        	Silex Request
	 * @param Application $app
	 *        	Silex Application
	 * @return RedirectResponse Redirects to a page
	 */
	public function signupPage($choice, Request $request, Application $app) {
		$args = [ 
				'title' => 'Signup Page',
				'page' => '',
				'error' => $app ['session']->get ( "Login_Error" ),
				'data' => $app ['session']->get ( "Login_Form_Details" ) 
		];
		
		$app ['session']->remove ( 'Login_Error' );
		$app ['session']->remove ( 'Login_Form_Details' );
		
		return $app ['twig']->render ( 'signup.html.twig', $args );
	}
	
	/**
	 * Signup Post Controller, handles the signup data
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function postSignup(Request $request, Application $app) {
		$username = $request->get ( 'username' );
		$password = $request->get ( 'password' );
		$repassword = $request->get ( 'repassword' );
		$firstname = $request->get ( 'firstname' );
		$lastname = $request->get ( 'lastname' );
		$dob = $request->get ( 'dob' );
		$gender = $request->get ( 'gender' );
		
		$database = new Database ();
		
		// check if user already exists.
		if ($database->checkUserExists ( $username )) {
			$app ['session']->set ( 'Login_Error', 'Username Already Exists with this name' );
			$app ['session']->set ( 'Login_Form_Details', $request->request->all () );
			return new RedirectResponse ( "/signup/1" );
			die ();
		}
		// check if passwords match
		if (! ($password === $repassword)) {
			$app ['session']->set ( 'Login_Error', 'Passwords Missmatch' );
			$app ['session']->set ( 'Login_Form_Details', $request->request->all () );
			return new RedirectResponse ( "/signup/1" );
			die ();
		}
		
		$user = User::createUser ( $username, $password, ROLES::$STUDENT );
		
		$barcode = rand ( 100000, 999999 );
		
		$studentData = [ 
				'id' => $user->getId (),
				'name' => $firstname,
				'surname' => $lastname,
				'dob' => $dob,
				'belt' => 1,
				'barcode' => $barcode 
		];
		$student = new Student ( $studentData );
		$student->create ();
		
		return new RedirectResponse ( "/success" );
	}
	
	/**
	 * Renders the success page after a successful registration.
	 * 
	 * @param Request $request        	
	 * @param Application $app        	
	 */
	public function successPage(Request $request, Application $app) {
		$args = [ 
				'title' => 'Success!',
				'page' => '' 
		];
		
		return $app ['twig']->render ( "success.html.twig", $args );
	}
	
	public function deleteStudent($barcode, Request $request, Application $app) {
		if(!$this->isAdmin($app))
			return new RedirectResponse('/login');
		
		$student = new Student([]);
		$student->getStudentFromDB($barcode);
		
		$student->delete();
		
		$user = new User("", $student->getId(), Roles::$STUDENT);
		$user->delete();
		
		return new RedirectResponse("/admin");
	}
}

?>
