<?php

declare(strict_types = 1);

use App\Routes;

/**
 * Default time zone to use application-wide. It is HIGHLY RECOMMENDED that you LEAVE THIS AS 'UTC', because it is
 * the universal time, even if you are located in a different time zone. Several utility functions require times to
 * be in UTC for proper usage, and there are utilities to convert UTC times to other time zones for display. You
 * probably should not change this!
 */
date_default_timezone_set('UTC');

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

Routes::addRoutes($router);
