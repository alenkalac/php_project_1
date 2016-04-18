<?php
use alen\Student;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

<<<<<<< HEAD
$app->get ( '/', 'alen\MainController::indexPage' );
$app->get ( '/test/{name}', 'alen\MainController::testPage' );
$app->get ('/test2', 'alen\MainController::macroTest');
=======
// Routes
require_once __DIR__ . '/../app/routes.php';
>>>>>>> 01e1b46936ae6b163921fc220313a3dfe3112d91

$app->run ();
?>
