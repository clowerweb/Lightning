<?php

namespace Core;

use App\Config;

/**
 * Router class
 *
 * PHP version 7.0
 */
class Router {
	// Associative array of routes
	protected $routes = [];
	// Parameters from the matched route
	protected $params = [];

	/**
	 * Add a route to the routes array
	 *
	 * @param string $route  - The route URL
	 * @param array  $params - Parameters (controller, action, etc.)
	 *
	 * @return void
	 */
	public function add(string $route, array $params = []) : void {
		$route = preg_replace('/\//', '\\/', $route);
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
		$route = '/^' . $route . '$/i';

		$this->routes[$route] = $params;
	}

	/**
	 * Match the route to the routes in the routes array, setting the $params
	 * property if a route is found
	 *
	 * @param string $url - The route URL
	 *
	 * @return boolean - True if match found, false if not
	 */
	public function match(string $url) : bool {
		$new    = rtrim($url, '/');
		$rchar  = substr($url, -1);
		$prefix = Utilities::isSSL() ? 'https://' : 'http://';

		if(Config::USE_URL_TRAILING_SLASH) {
			// redirect to url with /
			if($rchar !== '/') {
				$url = $url . '/';
				header('Location: ' . $prefix . $_SERVER['HTTP_HOST'] . '/' . $url, true, 301);
				exit;
			}
		} else {
			// redirect to url without /
			if($rchar === '/') {
				header('Location: ' . $prefix . $_SERVER['HTTP_HOST'] . '/' . $new, true, 301);
				exit;
			}
		}

		foreach($this->routes as $route => $params) {
			if(preg_match($route, $url, $matches)) {
				foreach($matches as $key => $match) {
					if(is_string($key)) {
						$params[$key] = $match;
					}
				}

				$this->params = $params;
				return true;
			}
		}

		return false;
	}

	/**
	 * Dispatch the route, creating the controller object and returning the action
	 *
	 * @param string $url - The route URL
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function dispatch(string $url) : void {
		$url = Utilities::removeQueryStringVars($url);

		if($this->match($url)) {
			$controller = $this->params['controller'];
			$controller = Utilities::convertToStudlyCaps($controller);
			$controller = $this->getNamespace() . $controller;

			if(class_exists($controller)) {
				$controller_object = new $controller($this->params);

				$action = $this->params['action'];
				$action = Utilities::convertToCamelCase($action);

				if(preg_match('/action$/i', $action) == 0) {
					$controller_object->$action();
				} else {
					throw new \Exception("Method $action in controller $controller not found", 404);
				}
			} else {
				throw new \Exception("Controller class $controller not found", 404);
			}
		} else {
			throw new \Exception("Route not found for $url", 404);
		}
	}

	/**
	 * Get the namespace for the controller class. The namespace defined in the
	 * route parameters is added if present.
	 *
	 * @return string - The request URL
	 */
	protected function getNamespace() : string {
		$namespace = 'App\Controllers\\';

		if(array_key_exists('namespace', $this->params)) {
			$namespace .= $this->params['namespace'] . '\\';
		}

		return $namespace;
	}
}
