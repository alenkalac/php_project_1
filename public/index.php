<?php
use alen\Student;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

// GET
$app->get ( '/', 		'alen\MainController::indexPage' );
$app->get ( '/admin', 	'alen\MainController::adminPage' );
$app->get ( '/login', 	'alen\MainController::loginPage' );
$app->get ( '/student/{barcode}', 'alen\MainController::studentPage' );

$app->get ( '/logout', 'alen\MainController::logoutPage' );

// POST
$app->post ( '/login', 	'alen\MainController::loginUser' );
$app->run ();
?>
