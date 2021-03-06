<?php

namespace App\Controllers;

use App\Models\Settings;
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
	public function indexAction(): void {
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
	private function create(): void {
		if(isset($_POST['email'])) {
			$user       = User::authenticate($_POST['email'], $_POST['password']);
			$remember   = isset($_POST['remember_me']);
			$settings   = Settings::getSettings();
			$activation = $settings['require_activation'];
			$approval   = $settings['require_approval'];

			if($user) {
				if(($user->is_active || $activation === '0') && ($user->is_approved || $approval === '0') && !$user->deactivated) {
					Auth::login($user, $remember);
					Flash::addMessage('Login successful');
					Utilities::redirect(Auth::getReturnToPage());
				} else {
					if(!$user->is_active) {
						Flash::addMessage('You have not yet activated your account. Please check your email and click the activation link. <a href="/register/resend-activation/' . $user->resend_token . '">Resend activation email</a>', Flash::WARNING);
					} else if(!$user->is_approved) {
						Flash::addMessage('Your account has not yet been approved by an administrator.', Flash::WARNING);
					} else if($user->deactivated) {
						Flash::addMessage('Your account has been deactivated.', Flash::ERROR);
					}
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
	public function destroyAction(): void {
		Auth::logout();
		Utilities::redirect('/login/show-logout-message');
	}

	/**
	 * Show a log out flash message after the session is destroyed
	 *
	 * @return void
	 */
	public function showLogoutMessageAction(): void {
		Flash::addMessage('You have successfully been logged out');
		Utilities::redirect('/');
	}
}
