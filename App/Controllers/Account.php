<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Auth;
use App\Models\User;
use App\Flash;
use App\Token;
use Core\Controller;
use Core\Utilities;
use Core\View;

/**
 * Account controller
 *
 * PHP version 7.2
 */
class Account extends Controller {
	public function indexAction() {
		$user      = Auth::getUser();
		$tzlist    = static::tz_list();
		$countries = array("Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

		if($user) {
			$verify_token = 'Account already verified.';

			if(!$user->author_id) {
				$token = new Token();
				$verify_token = $token->getValue();
				$_SESSION['verify_token'] = $verify_token;
			}

			View::renderTemplate('Account/index.twig', [
				'timezones'     => $tzlist,
				'countries'     => $countries,
				'profile_token' => $verify_token
			]);
		} else {
			Auth::rememberRequestedPage();
			Utilities::redirect('/login', '303');
		}
	}

	public function verifyAction() {
		$data = $_POST ?? null;
		$user = Auth::getUser();

		if(!$user) {
			throw new \Exception('User not found.');
		}

		if($data) {
			if($_SESSION['verify_token']) {
				$parts = parse_url($data['url']);

				parse_str($parts['query'], $query);

				if(User::verify((int)$query['uid'], $_SESSION['verify_token'], $user->username)) {
					echo 1;
				} else {
					echo 0;
				}
			} else {
				throw new \Exception('There was no token provided for verification.');
			}
		} else {
			throw new \Exception('POST data was empty for verification.');
		}
	}

	private static function tz_list() {
		$zones_array = array();
		$timestamp   = time();
		$dummy_dt    = new \DateTime();

		foreach(timezone_identifiers_list() as $key => $zone) {
			$tz = new \DateTimeZone($zone);

			date_default_timezone_set($zone);
			$zones_array[$key]['zone'] = $zone;
			$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
			$zones_array[$key]['offset'] = $tz->getOffset($dummy_dt);
			$zones_array[$key]['pretty'] = str_replace('/', ', ', $zone);
			$zones_array[$key]['pretty'] = str_replace('_', ' ', $zones_array[$key]['pretty']);
		}

		//return $zones_array;
		usort($zones_array, function($a, $b) {
			return $a['offset'] - $b['offset'];
		});

		return $zones_array;
	}

	/**
	 * Update user profile
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function updateAction() : void {
		$posted = $_POST ?? null;
		$user   = Auth::getUser();

		if($user && !Utilities::isEmpty($posted)) {
			$user_id = (int)$user->id;
			$errors  = [];
			$current = [
				'name'      => $user->name,
				'email'     => $user->email,
				'password'  => $user->password,
				'is_active' => $user->is_active,
				'country'   => $user->country,
				'timezone'  => $user->timezone,
				'bio'       => $user->bio,
				'website'   => $user->website,
				'facebook'  => $user->facebook,
				'twitter'   => $user->twitter,
				'instagram' => $user->instagram,
				'linkedin'  => $user->linkedin,
				'patreon'   => $user->patreon
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
				$current['name']      = Utilities::safeIO($posted['name']);
				$current['email']     = Utilities::safeIO($posted['email']);
				$current['country']   = Utilities::safeIO($posted['country']);
				$current['timezone']  = Utilities::safeIO($posted['timezone']);
				$current['bio']       = Utilities::externalLinks(Utilities::purifyOutput($posted['bio']));
				$current['website']   = Utilities::safeIO($posted['website']);
				$current['facebook']  = Utilities::safeIO($posted['facebook']);
				$current['twitter']   = Utilities::safeIO($posted['twitter']);
				$current['instagram'] = Utilities::safeIO($posted['instagram']);
				$current['linkedin']  = Utilities::safeIO($posted['linkedin']);
				$current['patreon']   = Utilities::safeIO($posted['patreon']);

				echo User::updateProfile($user_id, $current);
			} else {
				exit(json_encode($errors));
			}
		}
	}

	/**
	 * Validate that a username is unique (AJAX) for registration
	 *
	 * @return void
	 */
	public function validateUsernameAction() {
		$username = $_GET['username'] ?? null;
		$ignore   = $_GET['ignore_id'] ?? null;

		if($username) {
			$valid = ! User::usernameExists($username, $ignore);

			header('Content-Type: application/json');
			echo json_encode($valid);
		} else {
			Flash::addMessage('Sorry, an error has occurred: Username not found.', Flash::DANGER);
			View::renderTemplate('generic.twig');
		}
	}

	/**
	 * Validate that an email is unique (AJAX) for registration
	 *
	 * @return void
	 */
	public function validateEmailAction() {
		$email  = $_GET['email'] ?? null;
		$ignore = $_GET['ignore_id'] ?? null;

		if($email) {
			$valid = ! User::emailExists($email, $ignore);

			header('Content-Type: application/json');
			echo json_encode($valid);
		} else {
			Flash::addMessage('Sorry, an error has occurred: Email not found.', Flash::DANGER);
			View::renderTemplate('generic.twig');
		}
	}
}
