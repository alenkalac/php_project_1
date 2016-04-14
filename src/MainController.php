<?php

namespace alen;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MainController {
	
	/**
	 * Index Page Controller
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
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminPage(Request $request, Application $app) {
		
		$user = User::fromSerialize($app['session']->get("user"));
		
		if(!$user)
			return new RedirectResponse('/');
		if($user->getRole() != Roles::$ADMIN)
			return new RedirectResponse('/login');
		
		$database = new Database();
		$students = $database->getAllStudents();
		
		$args = [
				'name' => $user->getUsername(),
				'title' => 'Admin Page',
				'students' => $students,
				'page' => 'admin'
		];
	
		return $app ['twig']->render ( 'admin.html.twig', $args );
	}
	
	/**
	 * Student Page Controller
	 * @param String|int $barcode
	 * @param Request $request
	 * @param Application $app
	 */
	public function studentPage($barcode, Request $request, Application $app) {
		
		$user = User::fromSerialize($app['session']->get("user"));
		
		if(!$user)
			return new RedirectResponse('/');
		
		$student = new Student($barcode);
		
		if(!$student->getId()) 
			return new RedirectResponse('/');
		
		$args = [
				'name' => $user->getUsername(),
				'title' => 'Student Page ' . $barcode,
				'page' => 'student',
				'user' => $user,
				'student' => $student,
		];
		
		if($user->getRole() == Roles::$ADMIN)
			return $app ['twig']->render ( 'student.html.twig', $args );
		
		if( $user->getRole() == Roles::$STUDENT)
			if (strcmp($app['session']->get('barcode'), $barcode) == 0)
				return $app ['twig']->render ( 'student.html.twig', $args );
			
		return new RedirectResponse('/');
	}
	
	/**
	 * Barcode Page Controller.
	 * @param Request $request
	 * @param Application $app
	 */
	public function barcodePage(Request $request, Application $app) {
		$user = User::fromSerialize($app['session']->get("user"));
		
		$args = [
			'title' => 'Barcode Scanner',
			'page' => 'barcode',
		];
		
		if(!$user)
			return new RedirectResponse('/');
		if( !($user->getRole() == Roles::$ADMIN) )
			return new RedirectResponse('/login');
			
		return $app ['twig']->render ( 'barcode.html.twig', $args );
	}
	
	/**
	 * Logout Page Controller, Handles the logging out and deletes the sessions
	 * @param Request $request
	 * @param Application $app
	 */
	public function logoutPage(Request $request, Application $app) {
		$app['session']->clear();
		return new RedirectResponse('/');
	}
	
	/**
	 * Login User Post Request Controller
	 * @param Request $request
	 * @param Application $app
	 */
	public function loginUser(Request $request, Application $app) {
		$database = new Database();
		
		$username = $request->get('username');
		$password = $request->get('password');
		
		$user = $database->checkLogin($username, $password);
		
		$args = [
				'name' => $username,
				'title' => 'Login Form',
				'page' => 'login',
		];
		
		if($user == null) {
			$args['error'] = "Username or Password are incorrect, Try Again";
			return $app ['twig']->render ( 'login.html.twig', $args );
		}
		
		$userDataSerialized = serialize($user);
		
		$app['session']->set("user", $userDataSerialized);
		$app['session']->set("name", $username);
		
		if($user->getRole() == Roles::$STUDENT) {
			$app['session']->set("role", Roles::$STUDENT);
			$student = Student::getStudentById($user->getId());
			$app['session']->set("barcode", $student->getBarcode());
			return new RedirectResponse("/student/" . $student->getBarcode());
		}
		else if($user->getRole() == Roles::$ADMIN){
			$app['session']->set("role", Roles::$ADMIN);
			return new RedirectResponse("/admin");
		}
		else {
			return new RedirectResponse("/");
		}
	}
	
	/**
	 * Login Page Controller to display the Login Page
	 * @param Request $request
	 * @param Application $app
	 */
	public function loginPage(Request $request, Application $app) {
		$args = [
				'title' => 'Login Form',
				'page' => 'login',
		];
		
		return $app['twig']->render('login.html.twig', $args);
	}
	
	/**
	 * Marks a student as addended class using a barcode
	 * @param Request $request
	 * @param Application $app
	 */
	public function registerAttendance(Request $request, Application $app) {
		
		if($app['session']->get('role') != Roles::$ADMIN)
			return -1;
		
		$barcode = $request->get("barcode");
			
		if(empty($barcode) || strlen($barcode) != 6)
			return -1;
		
		$db = new Database();
		$result = $db->registerStudentAttendance($barcode);
		
		return $result;
		
	}
	
	public function eventXml($barcode, Request $request, Application $app) {
		
		$db = new Database();
		$att = $db->getAttendance($barcode);
		
		$args = [
				"attendace" => $att,
		];
		
		return $app['twig']->render('events.xml.twig', $args);
	}
}

?>