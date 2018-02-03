<?php

declare(strict_types = 1);

namespace App;

/**
 * Routes class
 *
 * PHP version 7.0
 */
class Routes {
	/**
	 * Add routes to the application
	 *
	 * @param object $router - the router class
	 *
	 * @return void
	 */
	public static function addRoutes(object $router) : void {
		// index
		$router->add('', ['controller' => 'Home', 'action' => 'index']);
		// route generic controller/action stuff
		$router->add('{controller}', ['action' => 'index']);
		$router->add('{controller}/{action}');
		$router->add('{controller}/{action}/{token:[\da-f]+}');

		$router->dispatch($_SERVER['QUERY_STRING']);
	}
}
