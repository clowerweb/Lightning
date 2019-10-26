<?php

namespace App;

use \App\Models\User;
use \App\Models\Remember;

/**
 * Auth class
 *
 * PHP version 7.2
 */
class Auth {
	/**
	 * Log a user in
	 *
	 * @param object $user - the user to log in
	 * @param boolean $remember - whether to remember the user
	 *
	 * @return void
	 */
	public static function login($user, $remember = false) {
		session_regenerate_id(true);
		$_SESSION['user_id'] = $user->id;

		if($remember) {
			if($user->rememberLogin()) {
				setcookie('remember_me', $user->token, $user->expire, '/');
			}
		}
	}

	/**
	 * Log a user out
	 *
	 * @return void
	 */
	public static function logout() {
		$_SESSION = [];

		if(ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();

			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params['path'],
				$params['domain'],
				$params['secure'],
				$params['httponly']
			);
		}

		session_destroy();
		static::forgetLogin();
	}

	/**
	 * Remember the page the user was trying to reach before being redirected to login
	 *
	 * @return void
	 */
	public static function rememberRequestedPage() {
		$_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
	}

	/**
	 * Get the originally requested page, or default to home
	 *
	 * @return string - the page to return to
	 */
	public static function getReturnToPage() {
		return $_SESSION['return_to'] ?? '/';
	}

	/**
	 * Get the current logged in user from the session or remember me cookie
	 *
	 * @return mixed - the user object if logged in, null if not
	 */
	public static function getUser() {
		if(isset($_SESSION['user_id'])) {
			return User::findByID($_SESSION['user_id']);
		} else {
			return static::loginFromCookie();
		}
	}

	/**
	 * Log the user in from the remember me cookie
	 *
	 * @return mixed - the user object if the cookie was found, false if not
	 */
	protected static function loginFromCookie() {
		$cookie = $_COOKIE['remember_me'] ?? false;

		if($cookie) {
			$remember = Remember::findByToken($cookie);

			if($remember && !$remember->hasExpired()) {
				$user = $remember->getUser();

				static::login($user, false);

				return $user;
			}
		}

		return false;
	}

	/**
	 * Forget a remembered login
	 *
	 * @return void
	 */
	protected static function forgetLogin() {
		$cookie = $_COOKIE['remember_me'] ?? false;

		if($cookie) {
			$remember = Remember::findByToken($cookie);

			if($remember) {
				$remember->delete();
			}

			setcookie('remember_me', '', time() - 3600);
		}
	}
}
