<?php

use Lightning\Router;

/**
 * Composer autoloader
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Dotenv
 */
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/**
 * Load config
 */
require_once dirname(__DIR__) . '/config/config.php';

/**
 * Routing
 */
$router = new Router();

// Add routes to the router
foreach ($config['routes'] as $routePattern => $routeParams) {
    $router->add($routePattern, $routeParams);
}

// Dispatch the router
$router->dispatch($_SERVER['QUERY_STRING']);
