<?php

namespace App\Controllers;

use \Core\View;
use \Core\Controller;
use \App\Flash;
use \App\Models\User;
use \App\Models\Settings;

/**
 * Register controller
 *
 * PHP version 7.2
 */
class Register extends Controller {
	private $settings;

	/**
	 * Check if user registrations are allowed, if not, remember the
	 * requested page, flash a message, and redirect to login
	 *
	 * @return void
	 */
	public function before() {
		$this->settings = Settings::getSettings();

		if(!$this->settings['allow_registration']) {
			Flash::addMessage("Sorry, account registration is not open at this time.", Flash::INFO);
			Utilities::redirect('/');
		}
	}

	/**
	 * Show the registration index
	 *
	 * @return void
	 */
	public function indexAction() {
		$data = $_POST ?? null;

		if($data) {
			$this->create();
		} else {
			View::renderTemplate('Register/index.twig');
		}
	}

	/**
	 * Save a new user
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	private function create() {
		if(!empty($_POST)) {
			$user = new User($_POST);

			if($user->save()) {
				$_SESSION['resend_token'] = $user->resend_token;

				if($user->sendActivationEmail()) {
					Utilities::redirect('/register/success');
				} else {
					$email = $_POST['email'];
					throw new \Exception("Failed to send activation email to $email");
				}
			// validation errors
			} else {
				View::renderTemplate('Register/index.twig', [
					'user' => $user
				]);
			}
		// this page was accessed without posting anything
		} else {
			Utilities::redirect('/register');
		}
	}

	/**
	 * Show the registration success page
	 *
	 * @return void
	 */
	public function successAction() {
		$token = $_SESSION['resend_token'] ?? null;

		View::renderTemplate('Register/success.twig', [
			'resend_token' => $token
		]);
	}

	/**
	 * Activate a new account
	 *
	 * @return void
	 */
	public function activateAction() {
		if(isset($this->route_params['token'])) {
			if(User::activate($this->route_params['token'])) {
				Utilities::redirect('/register/activated');
			} else {
				Utilities::redirect('/register/activation-failed');
			}
		}

		Utilities::redirect('/register');
	}

	/**
	 * Show the activation success page
	 *
	 * @return void
	 */
	public function activatedAction() {
		View::renderTemplate('Register/activation-success.twig');
	}

	/**
	 * Show the activation failed page
	 *
	 * @return void
	 */
	public function activationFailedAction() {
		View::renderTemplate('Register/activation-failed.twig');
	}

	/**
	 * Resend the user's activation email
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function resendActivationAction() {
		if(isset($this->route_params['token'])) {
			$userObj = new User();

			if($user = $userObj->findByResendToken($this->route_params['token'])) {
				if($user->updateActivationHash()) {
					if($user->sendActivationEmail()) {
						Utilities::redirect('/register/activation-resent');
					}

					throw new \Exception("Failed to resend activation email to $user->email");
				}

				throw new \Exception("Failed to update activation hash for $user->email");
			}

			throw new \Exception("Failed to find user by resend token");
		}

		Utilities::redirect('/register');
	}

	/**
	 * Show the activation email resent page
	 *
	 * @return void
	 */
	public function activationResentAction() {
		View::renderTemplate('Register/activation-resent.twig');
	}
}
