<?php

declare(strict_types = 1);

namespace App;

use \Exception;
use Core\Router;

/**
 * Routes class
 *
 * PHP version 7.2
 */
class Routes {
	/**
	 * Add routes to the application
     *
     * @throws Exception
	 *
	 * @return void
	 */
	public static function addRoutes() {
	    $router = new Router();

		// index
		$router->add('', ['controller' => 'Home', 'action' => 'index']);
		// log out
        $router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
		// route generic controller/action stuff
		$router->add('{controller}', ['action' => 'index']);
		$router->add('{controller}/{action}');
		$router->add('{controller}/{action}/{token:[\da-f]+}');

		$router->dispatch($_SERVER['QUERY_STRING']);
	}
}
