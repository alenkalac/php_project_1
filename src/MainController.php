<?php

namespace alen;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MainController {
	public function indexPage(Request $request, Application $app) {
		$args = [
				'title' => 'ITB Karate | Home Page',
				'page' => 'home'
		];
		
		return $app ['twig']->render ( 'index.html.twig', $args );
	}
	
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
	
	public function studentPage($barcode, Request $request, Application $app) {
		
		$user = User::fromSerialize($app['session']->get("user"));
		
		$student = new Student($barcode);
		
		$args = [
				'name' => $user->getUsername(),
				'title' => 'Student Page ' . $barcode,
				'page' => 'student',
				'user' => $user,
				'student' => $student,
		];
		
		if(!$user)
			return new RedirectResponse('/');
		if( !($user->getRole() == Roles::$STUDENT || $user->getRole() == Roles::$ADMIN) ) {
			return new RedirectResponse('/login');
		}
		
		return $app ['twig']->render ( 'student.html.twig', $args );
	}
	
	public function logoutPage(Request $request, Application $app) {
		$app['session']->clear();
		return new RedirectResponse('/');
	}
	
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
			return new RedirectResponse("/student");
		}
		else if($user->getRole() == Roles::$ADMIN){
			$app['session']->set("role", Roles::$ADMIN);
			return new RedirectResponse("/admin");
		}
		else {
			return new RedirectResponse("/");
		}
	}
	
	public function loginPage(Request $request, Application $app) {
		$args = [
				'title' => 'test',
				'page' => 'login',
		];
		
		return $app['twig']->render('login.html.twig', $args);
	}
}

?>