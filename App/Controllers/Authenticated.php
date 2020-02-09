<?php

namespace App\Controllers;

use \Core\Controller;

/**
 * Authenticated controller
 *
 * PHP version 7.2
 */
abstract class Authenticated extends Controller {
	/**
	 * Runs before anything else in this controller will run
	 *
	 * @return void
	 */
	protected function before() {
		$this->requireLogin();
	}
}
