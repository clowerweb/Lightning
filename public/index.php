<?php
/**
 * Lightning Main Entry Point
 * 
 * This file serves as the entry point for API requests only.
 * If it's reached by any other request, it's a server misconfiguration.
 * 
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

use Dotenv\Dotenv;
use Lightning\Config\Router;
use Lightning\Helpers\Utilities;

// Get the request URI & parse the segments
$requestUri   = $_SERVER['REQUEST_URI'];
$segments     = explode('/', trim($requestUri, '/'));
$firstSegment = $segments[0] ?? null;

// Make sure it's an API request.
if ($firstSegment !== 'api') {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not Found. This entry point is for API requests only.']);
    exit;
}

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

// Define the base directory
define('BASE_PATH', Utilities::getAbsRoot());

// Handle CORS
header('Access-Control-Allow-Origin: *');

// Load environment variables
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}

/**
 * Error and exception handler
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_error_handler('Lightning\Helpers\Error::errorHandler');
set_exception_handler('Lightning\Helpers\Error::exceptionHandler');

// All API requests are routed to the API controller
$router = new Router();
$router->route('ApiController', 'process');
