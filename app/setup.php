<?php
$templatePath = __DIR__ . '/../templates';

$app = new Silex\Application ();

$app ['debug'] = true;

$app->register ( new Silex\Provider\TwigServiceProvider (), array (
		'twig.path' => $templatePath 
) );

$app->register(new Silex\Provider\SessionServiceProvider());