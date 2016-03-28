<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

$app->get ( '/', 'alen\MainController::indexPage' );
$app->get ( '/test/{name}', 'alen\MainController::testPage' );

$app->run ();
?>
