<?php

declare(strict_types = 1);

namespace App\Controllers;

use \Exception;
use Core\View;
use Core\Controller;

/**
 * Home controller
 *
 * PHP version 7.2
 */
class Home extends Controller {
	/**
	 * Show the index page
	 *
	 * @throws Exception from Twig\Error
	 *
	 * @return void
	 */
	public function indexAction() {
		View::renderTemplate('Home/index.twig');
	}
}
