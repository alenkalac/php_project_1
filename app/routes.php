<?php
	use alen\MainController;

	//GET
	$app->get ( '/', 'alen\MainController::indexPage' );
	$app->get ( '/admin', 'alen\MainController::adminPage' );
	$app->get ( '/login', 'alen\MainController::loginPage' );
	$app->get ( '/admin/barcode', 'alen\MainController::barcodePage');
	$app->get ( '/student/{barcode}', 'alen\MainController::studentPage' );
	$app->get ( '/logout', 'alen\MainController::logoutPage' );
	$app->get ( '/events/{barcode}', 'alen\MainController::eventXml');

	//POST
	$app->post ( '/login', 	'alen\MainController::loginUser' );
	$app->post ( '/admin/barcode', 'alen\MainController::registerAttendance' );

	$app->error(function($e, $code) use($app){
		$mainController = new MainController();
		return $mainController->errorPage($code, $app);
	});

?>
