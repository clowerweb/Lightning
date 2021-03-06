<?php

namespace App\Controllers;

use \Core\View;
use \Core\Controller;
use \App\Models\User;
use Exception;

/**
 * Password controller
 *
 * PHP version 7.2
 */
class Password extends Controller {
	/**
	 * Show the forgot password page
     *
     * @throws Exception
	 *
	 * @return void
	 */
	public function forgotAction(): void {
		View::renderTemplate('Password/forgot.twig');
	}

	/**
	 * Send a password reset link to the email
     *
     * @throws Exception
	 *
	 * @return void
	 */
	public function requestResetAction(): void {
		User::sendPasswordReset($_POST['email']);
		View::renderTemplate('Password/reset-requested.twig');
	}

	/**
	 * Show the password reset form
     *
     * @throws Exception
	 *
	 * @return void
	 */
	public function resetAction(): void {
		$token = $this->route_params['token'];

		View::renderTemplate('Password/reset.twig', [
			'token' => $token
		]);
	}

	/**
	 * Resets the password
     *
     * @throws Exception
	 *
	 * @return void
	 */
	public function resetPasswordAction(): void {
		$token = $_POST['token'];
		$user  = $this->getUserOrExit($token);

		if($user->resetPassword($_POST['password'])) {
			View::renderTemplate('Password/reset-success.twig');
		} else {
			View::renderTemplate('Password/reset.twig', [
				'token' => $token,
				'user'  => $user
			]);
		}
	}

	/**
	 * Either find the user by reset token, or exit with a message
	 *
	 * @param string $token - Password reset token
     *
     * @throws Exception
	 *
	 * @return mixed - User object if found and not expired, null if not
	 */
	protected function getUserOrExit($token) {
		$user  = User::findByPasswordReset($token);

		if($user) {
			return $user;
		}

		View::renderTemplate('Password/invalid-token.twig');
		exit;
	}
}
