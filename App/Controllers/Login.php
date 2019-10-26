<?php

namespace App\Controllers;

use \Exception;
use Core\View;
use Core\Controller;
use Core\Utilities;
use App\Models\User;
use App\Auth;
use App\Flash;

/**
 * Login controller
 *
 * PHP version 7.2
 */
class Login extends Controller {
    /**
     * Show the login page
     *
     * @throws Exception
     *
     * @return void
     */
	public function indexAction() {
		$data = $_POST ?? null;

		if($data) {
			$this->create();
		} else {
			View::renderTemplate('Login/index.twig');
		}
	}

	/**
	 * Log in a user
     *
     * @throws Exception
	 *
	 * @return void
	 */
	private function create() {
		if(isset($_POST['email'])) {
			$user     = User::authenticate($_POST['email'], $_POST['password']);
			$remember = isset($_POST['remember_me']);

			if($user) {
				if($user->is_active) {
					Auth::login($user, $remember);
					Flash::addMessage('Login successful');
					Utilities::redirect(Auth::getReturnToPage());
				} else {
					Flash::addMessage('You have not yet activated your account. Please check your email and click the activation link. <a href="/register/resend-activation/' . $user->resend_token . '">Resend activation email</a>', Flash::INFO);
				}
			} else {
				Flash::addMessage('The email or password was incorrect, please try again', Flash::WARNING);
			}

			View::renderTemplate('Login/index.twig', [
				'email'    => $_POST['email'],
				'remember' => $remember
			]);
		} else {
			Utilities::redirect('/login');
		}
	}

	/**
	 * Log a user out
	 *
	 * @return void
	 */
	public function destroyAction() {
		Auth::logout();
		Utilities::redirect('/login/show-logout-message');
	}

	/**
	 * Show a log out flash message after the session is destroyed
	 *
	 * @return void
	 */
	public function showLogoutMessageAction() {
		Flash::addMessage('You have successfully been logged out');
		Utilities::redirect('/');
	}
}
