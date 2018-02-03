<?php

declare(strict_types = 1);

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
