<?php
	use alen\MainController;

	//GET
	$app->get ( '/', 'alen\MainController::indexPage' );
	$app->get ( '/admin', 'alen\MainController::adminPage' );
	$app->get ( '/login', 'alen\MainController::loginPage' );
	$app->get ( '/admin/barcode', 'alen\MainController::barcodePage');
	$app->get ( '/admin/techniques', 'alen\MainController::techPage');
	$app->get ( '/admin/edit/{barcode}', 'alen\MainController::editPage');
	$app->get ( '/student/{barcode}', 'alen\MainController::studentPage' );
	$app->get ( '/logout', 'alen\MainController::logoutPage' );
	$app->get ( '/events/{barcode}', 'alen\MainController::eventXml');
	$app->get ( '/signup/{choice}', 'alen\MainController::signupPage');
	$app->get ( '/success', 'alen\MainController::successPage');
	$app->get ( '/admin/delete/{barcode}', 'alen\MainController::deleteStudent');
	$app->get ( '/contact', 'alen\MainController::contactPage');
	$app->get ( '/syllabus', 'alen\MainController::syllabusPage');
	
	//POST
	$app->post ( '/login', 	'alen\MainController::postLoginUser' );
	$app->post ( '/admin/barcode', 'alen\MainController::postRegisterAttendance' );
	$app->post ( '/admin/edit', 'alen\MainController::postEditDetails');
	$app->post ( '/signup', 'alen\MainController::postSignup' );

	$app->error(function($e, $code) use($app){
		$mainController = new MainController();
		return $mainController->errorPage($code, $app);
	});

?>
