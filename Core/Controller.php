<?php

declare(strict_types = 1);

namespace Core;

use App\Auth;
use App\Flash;
use \Exception;

/**
 * Controller class
 *
 * PHP version 7.2
 */
abstract class Controller {
	protected $route_params = [];
	private $user;

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
	protected function before(): void {}

	/**
	 * After filter - called after an action method
	 *
	 * @return void
	 */
	protected function after(): void {}

	/**
	 * Require the user to be logged in before giving access to the requested page.
	 * Remember the requested page for later, then redirect to the login page.
	 *
	 * @return void
	 */
	public function requireLogin(): void {
		if (! Auth::getUser()) {
			Flash::addMessage('Please log in to access that page', Flash::INFO);
			Auth::rememberRequestedPage();

			Utilities::redirect('/login', 303);
		}
	}

	/**
	 * Require the user to be logged in before giving access to the requested page.
	 * Remember the requested page for later, then redirect to the login page.
	 *
	 * @return void
	 */
	public function requireAdmin(): void {
		$this->user = Auth::getUser();

		if(! $this->user || $this->user->role !== '1') {
			if(! $this->user) {
				Flash::addMessage("Please sign in.", Flash::INFO);
			} else if($this->user->role !== '1') {
				Flash::addMessage("You don't have permission to do that.", Flash::INFO);
			}

			Auth::rememberRequestedPage();
			Utilities::redirect('/login');
		}
	}
}
