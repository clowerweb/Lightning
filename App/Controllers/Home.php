<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\Utilities;
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
    	// Uncomment this if this application requires registered users to access
        /*$this->user = Auth::getUser();

        if(! $this->user || ! $this->user->role == '1') {
        	if(! $this->user) {
				Flash::addMessage("Please sign in.", Flash::INFO);
			} else if(! $this->user->role == '1') {
				Flash::addMessage("You don't have permission to do that.", Flash::INFO);
			}

            Auth::rememberRequestedPage();
            Utilities::redirect('/login');
        }*/
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
