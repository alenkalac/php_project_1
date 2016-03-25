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
}

?>