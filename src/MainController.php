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
		$success = $database->checkLogin($username, $password);
		
		echo $success;
		
		die();
		$args = [
				'name' => $username,
				'title' => 'test'
		];
	
		return $app ['twig']->render ( 'index.html.twig', $args );
	}
	
	public function loginPage(Request $request, Application $app) {
		$args = [
				'title' => 'test'
		];
		
		return $app['twig']->render('login.html.twig', $args);
	}
}

?>