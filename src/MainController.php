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
		if(!$user->getRole() != 0)
			return new RedirectResponse('/login');
		
		$database = new Database();
		$students = $database->getAllStudents();
		
		$args = [
				'name' => 'Alen2',
				'title' => 'test',
				'students' => $students,
				'page' => 'admin'
		];
	
		return $app ['twig']->render ( 'admin.html.twig', $args );
	}
	
	public function studentPage(Request $request, Application $app) {
		
		$user = User::fromSerialize($app['session']->get("user"));
		
		$args = [
				'name' => 'Alen2',
				'title' => 'test',
				'page' => 'student',
		];
		
		if(!$user)
			return new RedirectResponse('/');
		if(!$user->getRole() != 1)
			return new RedirectResponse('/login');
	
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
		$userDataSerialized = serialize($user);
		
		$app['session']->set("user", $userDataSerialized);
		
		$args = [
				'name' => $username,
				'title' => 'test',
				'page' => 'login',
		];
		
		if($user == null)
			return $app ['twig']->render ( 'error.html.twig', $args );
		
		else if($user->getRole() == 0) {
			return new RedirectResponse("/student");
		}
		else if($user->getRole() == 1){
			return new RedirectResponse("/admin");
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