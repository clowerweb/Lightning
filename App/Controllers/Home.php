<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Auth;
use \Exception;
use Core\View;
use Core\Controller;

/**
 * Home controller
 *
 * PHP version 7.2
 */
class Home extends Controller {
    private $user;

    public function before(): void {
		$this->user = Auth::getUser();
    }

	/**
	 * Show the index page
	 *
	 * @throws Exception from Twig\Error
	 *
	 * @return void
	 */
	public function indexAction(): void {
		View::renderTemplate('Home/index.twig', [
		    'user' => $this->user
        ]);
	}
}
