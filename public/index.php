<?php

declare(strict_types = 1);

/**
 * Please read the config comments on this and don't change it!
 */
date_default_timezone_set(Config::DEFAULT_TIME_ZONE);

/**
 * Composer autoloader
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error and exception handler
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();

// index
$router->add('', ['controller' => 'Home', 'action' => 'index']);
// route generic controller/action stuff
$router->add('{controller}', ['action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/{token:[\da-f]+}');

$router->dispatch($_SERVER['QUERY_STRING']);
