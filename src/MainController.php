<?php

namespace alen;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class MainController {
	public function indexPage(Request $request, Application $app) {
		$args = [ 
				'name' => 'Alen2',
				'title' => 'test' 
		];
		
		return $app ['twig']->render ( 'index.html.twig', $args );
	}
	
	public function testPage($name, Request $request, Application $app) {
		$args = [
				'name' => $name,
				'title' => 'test'
		];
	
		return $app ['twig']->render ( 'index.html.twig', $args );
	}
	
	public function loginUser(Request $request, Application $app) {
		
		$username = $request->get('username');
		$password = $request->get('password');
		
		$database = new Database();
		$user = $database->checkLogin($username, $password);
		
		$args = [
				'name' => $username,
				'title' => 'test'
		];
		
		if($user == null)
			return $app ['twig']->render ( 'error.html.twig', $args );
		
		else if($user->getRole() == 0) {
			$app->get('session')->set('user',  array ('user' => $user));
			return $app ['twig']->render ( 'student.html.twig', $args );
		}
		else if($user->getRole() == 1){
			return $app ['twig']->render ( 'admin.html.twig', $args );
		}
		
		
	
		
	}
	
	public function loginPage(Request $request, Application $app) {
		$args = [
				'title' => 'test'
		];
		
		return $app['twig']->render('login.html.twig', $args);
	}
}

?>