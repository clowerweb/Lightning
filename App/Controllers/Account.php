<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Auth;
use App\Models\User;
use App\Flash;
use Core\Controller;
use Core\Utilities;
use Core\View;
use Exception;

/**
 * Account controller
 *
 * PHP version 7.2
 */
class Account extends Controller {
	public function indexAction(): void {
        Auth::rememberRequestedPage();
        Utilities::redirect('/login', 303);
	}

	/**
	 * Update user profile
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function updateAction(): void {
		$posted = $_POST ?? null;
		$user   = Auth::getUser();

		if($user && !Utilities::isEmpty($posted)) {
			$user_id = (int)$user->id;
			$errors  = [];
			$current = [
				'name'      => $user->name,
				'email'     => $user->email,
				'password'  => $user->password,
				'is_active' => $user->is_active
			];

			if(strtolower($posted['email']) !== strtolower($current['email'])) {
				if(!filter_var($posted['email'], FILTER_VALIDATE_EMAIL)) {
					$errors[] = 'Email address appears to be invalid.';
				}

				if(User::emailExists($posted['email'])) {
					$errors[] = 'Sorry, that email address is already in use.';
				}
			}

			if(!Utilities::isEmpty($posted['current_password']) && !Utilities::isEmpty(trim($posted['new_password']))) {
				if(!password_verify($posted['current_password'], $current['password'])) {
					$errors[] = 'Current password is incorrect.';
				} else {
					$current['password'] = password_hash($posted['new_password'], PASSWORD_DEFAULT);
				}
			}

			if(Utilities::isEmpty($errors)) {
				$current['name']  = Utilities::purifyOutput($posted['name']);
				$current['email'] = Utilities::purifyOutput($posted['email']);

				echo User::updateProfile($user_id, $current);
			} else {
				exit(json_encode($errors));
			}
		}
	}

	/**
	 * Validate that an email is unique (AJAX) for registration
     *
     * @throws Exception
	 *
	 * @return void
	 */
	public function validateEmailAction(): void {
		$email  = $_GET['email'] ?? '';
		$ignore = $_GET['ignore_id'] ?? null;

		if($email) {
			$valid = !User::emailExists($email, $ignore);

			header('Content-Type: application/json');
			echo json_encode($valid);
		} else {
			Flash::addMessage('Sorry, an error has occurred: Email not found.', Flash::DANGER);
			View::renderTemplate('generic.twig');
		}
	}
}
