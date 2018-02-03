<?php

declare(strict_types = 1);

namespace Core;

use \Exception;

/**
 * Controller class
 *
 * PHP version 7.0
 */
abstract class Controller {
	protected $route_params = [];

	public function __construct(array $route_params) {
		$this->route_params = $route_params;
	}

	/**
	 * __call magic method
	 *
	 * @param string $name - name of the method to call
	 * @param array  $args - arguments passed to the method
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function __call(string $name, array $args) {
		$name = $name . 'Action';

		if(method_exists($this, $name)) {
			if($this->before() !== false) {
				call_user_func_array([$this, $name], $args);
				$this->after();
			}
		} else {
			throw new Exception("Method $name not found in controller " . get_class($this));
		}
	}

	/**
	 * Before filter - called before an action method
	 *
	 * @return void
	 */
	protected function before() {}

	/**
	 * After filter - called after an action method
	 *
	 * @return void
	 */
	protected function after() {}

	/**
	 * Redirect to a different page
	 *
	 * @param string $url  - The URL to redirect to
	 * @param int    $code - Optional. The HTTP code. Defaults to 303 "See Other"
	 *
	 * @return void
	 */
	public function redirect(string $url, int $code = 303) {
		$prefix = Utilities::isSSL() ? 'https://' : 'http://';
		header('Location: ' . $prefix . $_SERVER['HTTP_HOST'] . $url, true, $code);
		exit;
	}
}
