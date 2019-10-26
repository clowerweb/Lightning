<?php

namespace App\Models;

use PDO;
use App\Token;
use App\Mail;
use Core\Model;
use Core\View;
use Core\Utilities;

/**
 * User model
 *
 * PHP version 7.2
 */
class User extends Model {
	// Public properties
	public $errors = [];
	public $id;
	public $author_id;
	public $username;
	public $password;
	public $email;
	public $terms;
	public $resend_token;
	public $is_active;
	public $role;
	public $token;
	public $expire;
	public $registered_date;
	public $name;
	public $country;
	public $timezone;
	public $bio;
	public $website;
	public $facebook;
	public $twitter;
	public $instagram;
	public $linkedin;
	public $patreon;
	public $verify_token;
	// Private properties
	private $password_reset_token;
	private $password_reset_expiry;
	private $activation_hash;
	private $activation_token;

	/**
	 * Constructor
	 *
	 * @param array $data - the initial property values
	 */
	public function __construct($data = []) {
		foreach($data as $key => $val) {
			$this->$key = $val;
		}
	}

	/**
	 * Get all users as an associative array
	 *
	 * @return array
	 */
	public static function getAll() {
		$db   = static::getDB();
		$stmt = $db->query("
			SELECT
				`id`,
				`name`,
				`username`,
				`email`,
				`is_active`,
				`is_verified`,
				`is_banned`,
				`role`,
				`registered_date`,
				`country`
			FROM
				`users`;
		");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Count all users
	 *
	 * @return int
	 */
	public static function getCount() {
		$db   = static::getDB();
		$stmt = $db->query("
			SELECT
				COUNT(*) AS `num_users`
			FROM
				`users`
		");

		$res = $stmt->fetch();

		return $res['num_users'];
	}

	/**
	 * Count active users
	 *
	 * @return int
	 */
	public static function getActive() {
		$db   = static::getDB();
		$stmt = $db->query("
			SELECT
				COUNT(*) AS `active`
			FROM
				`users`
			WHERE
				`is_active` = 1;
		");

		$res = $stmt->fetch();

		return $res['active'];
	}

	/**
	 * Count inactive users
	 *
	 * @return int
	 */
	public static function getInactive() {
		$db   = static::getDB();
		$stmt = $db->query("
			SELECT
				COUNT(*) AS `inactive`
			FROM
				`users`
			WHERE
				`is_active` = 0;
		");

		$res = $stmt->fetch();

		return $res['inactive'];
	}

	/**
	 * Count banned users
	 *
	 * @return int
	 */
	public static function getNumBanned() {
		$db   = static::getDB();
		$stmt = $db->query("
			SELECT
				COUNT(*) AS `banned`
			FROM
				`users`
			WHERE
				`is_banned` = 1;
		");

		$res = $stmt->fetch();

		return $res['banned'];
	}

	/**
	 * Save a new user to the users table
	 *
	 * @return boolean - true if user saved successfully, false if not
	 */
	public function save() {
		$this->validate();

		if(empty($this->errors)) {
			$this->password = password_hash($this->password, PASSWORD_DEFAULT);

			// account activation token
			$token = new Token();
			$this->activation_hash  = $token->getHash();
			$this->activation_token = $token->getValue();

			// for resending activation emails
			$resend = new Token();
			$this->resend_token = $resend->getValue();

			$this->registered_date = date('Y-m-d H:i:s');

			$sql = "
				INSERT INTO
					`users` (
						`username`,
						`email`,
						`password`,
						`activation_hash`,
						`resend_token`,
						`registered_date`
					)
				VALUES (
					:username,
					:email,
					:password,
					:hash,
					:resend_token,
					:registered_date
				);
			";

			$db   = static::getDB();
			$stmt = $db->prepare($sql);

			$stmt->bindValue(':username',        $this->username,        PDO::PARAM_STR);
			$stmt->bindValue(':email',           $this->email,           PDO::PARAM_STR);
			$stmt->bindValue(':password',        $this->password,        PDO::PARAM_STR);
			$stmt->bindValue(':hash',            $this->activation_hash, PDO::PARAM_STR);
			$stmt->bindValue(':resend_token',    $this->resend_token,    PDO::PARAM_STR);
			$stmt->bindValue(':registered_date', $this->registered_date, PDO::PARAM_STR);

			return $stmt->execute();
		}

		return false;
	}

	public static function updateProfile(int $user_id, array $data) : bool {
		$sql = "
			UPDATE
				`users`
			SET
				`name`      = :name,
				`email`     = :email,
				`password`  = :password,
				`country`   = :country,
				`timezone`  = :timezone,
				`bio`       = :bio,
				`website`   = :website,
				`facebook`  = :facebook,
				`twitter`   = :twitter,
				`instagram` = :instagram,
				`linkedin`  = :linkedin,
				`patreon`   = :patreon
			WHERE
				`id` = :user_id
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':name',      $data['name'],      PDO::PARAM_STR);
		$stmt->bindValue(':email',     $data['email'],     PDO::PARAM_STR);
		$stmt->bindValue(':password',  $data['password'],  PDO::PARAM_STR);
		$stmt->bindValue(':country',   $data['country'],   PDO::PARAM_STR);
		$stmt->bindValue(':timezone',  $data['timezone'],  PDO::PARAM_STR);
		$stmt->bindValue(':bio',       $data['bio'],       PDO::PARAM_STR);
		$stmt->bindValue(':website',   $data['website'],   PDO::PARAM_STR);
		$stmt->bindValue(':facebook',  $data['facebook'],  PDO::PARAM_STR);
		$stmt->bindValue(':twitter',   $data['twitter'],   PDO::PARAM_STR);
		$stmt->bindValue(':instagram', $data['instagram'], PDO::PARAM_STR);
		$stmt->bindValue(':linkedin',  $data['linkedin'],  PDO::PARAM_STR);
		$stmt->bindValue(':patreon',   $data['patreon'],   PDO::PARAM_STR);
		$stmt->bindValue(':user_id',   $user_id,           PDO::PARAM_INT);

		return $stmt->execute();
	}

	/**
	 * Validate the values submitted by the user and add errors to the errors array
	 *
	 * @return void
	 */
	public function validate() {
		if($this->username == '') {
			$this->errors[] = 'Username is required.';
		}

		if(static::usernameExists($this->username, $this->id ?? null)) {
			$this->errors[] = 'The username you entered is taken.';
		}

		if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			$this->errors[] = 'Invalid email.';
		}

		if(static::emailExists($this->email, $this->id ?? null)) {
			$this->errors[] = 'The email you entered is already registered.';
		}

		if(strlen($this->password) < 6) {
			$this->errors[] = 'Minimum password length is 6 characters.';
		}

		if(preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
			$this->errors[] = 'Password must contain at least one letter.';
		}

		if(preg_match('/.*\d+.*/i', $this->password) == 0) {
			$this->errors[] = 'Password needs at least one number';
		}

		if(!$this->terms || $this->terms != 'on') {
			echo $this->terms;
			$this->errors[] = 'You must agree to the Privacy Policy and Terms of Service.';
		}
	}

	/**
	 * Make sure the username is unique
	 *
	 * @param string $username - the username to check
	 * @param boolean $ignore - return false if the ID matches the user ID (to make our validate method work for updates)
	 *
	 * @return boolean - true if record exists, false if it's unique
	 */
	public static function usernameExists($username, $ignore = null) {
		$user = static::findByUsername($username);

		if($user) {
			if($user->id !== $ignore) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Find a user by username
	 *
	 * @param string $username - the username to search for
	 *
	 * @return mixed - user object if found, false if not
	 */
	public static function findByUsername($username) {
		$sql = "
			SELECT
				`id`,
				`name`,
				`username`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`,
				`is_verified`,
				`is_banned`,
				`role`,
				`registered_date`,
				`country`,
				`timezone`,
				`bio`,
				`website`,
				`facebook`,
				`twitter`,
				`instagram`,
				`linkedin`,
				`patreon`
			FROM
				`users`
			WHERE
				`username` = :username;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':username', $username, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();

		return $stmt->fetch();
	}

	/**
	 * Make sure the email is unique
	 *
	 * @param string $email - the email address to check
	 * @param boolean $ignore - return false if the ID matches the user ID (to make our validate method work for updates)
	 *
	 * @return boolean - true if record exists, false if it's unique
	 */
	public static function emailExists($email, $ignore = null) {
		$user = static::findByEmail($email);

		if($user) {
			if($user->id != $ignore) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Find a user by email address
	 *
	 * @param string $email - the email address to search for
	 *
	 * @return mixed - user object if found, false if not
	 */
	public static function findByEmail($email) {
		$sql = "
			SELECT
				`id`,
				`name`,
				`username`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`,
				`is_verified`,
				`role`,
				`registered_date`,
				`country`,
				`timezone`,
				`bio`,
				`website`,
				`facebook`,
				`twitter`,
				`instagram`,
				`linkedin`
			FROM
				`users`
			WHERE
				`email` = :email;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();

		return $stmt->fetch();
	}

	/**
	 * Authenticate a user by email and password
	 *
	 * @param string $email - the email address
	 * @param string $password - the password
	 *
	 * @return mixed - user object if auth is successful, false if not
	 */
	public static function authenticate($email, $password) {
		$user = static::findByEmail($email);

		if($user) {
			if(password_verify($password, $user->password)) {
				return $user;
			}
		}

		return false;
	}

	/**
	 * Find a user by ID
	 *
	 * @param int $id - the user ID to get
	 *
	 * @return mixed - user object if found, false if not
	 */
	public static function findByID($id) {
		$sql = "
			SELECT
				`id`,
				`name`,
				`username`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`,
				`is_verified`,
				`is_banned`,
				`role`,
				`registered_date`,
				`country`,
				`timezone`,
				`bio`,
				`website`,
				`facebook`,
				`twitter`,
				`instagram`,
				`linkedin`,
				`patreon`
			FROM
				`users`
			WHERE
				`id` = :id;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();

		return $stmt->fetch();
	}

	/**
	 * Remember the login by generating a new unique token hash and storing it in
	 * the remembered_logins table for the user id
	 *
	 * @return boolean - true if the login was remembered, false if not
	 */
	public function rememberLogin() {
		$token = new Token();

		$this->token  = $token->getValue();
		$this->expire = time() + 60 * 60 * 24 * 30; // 30 days

		$sql = "
			INSERT INTO
				`remembered_logins` (
					`token_hash`,
					`user_id`,
					`expires_date`
				)
			VALUES (
				:token_hash,
				:user_id,
				:expires_date
			);
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':token_hash', $token->getHash(), PDO::PARAM_STR);
		$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$stmt->bindValue(':expires_date', date('Y-m-d H:i:s', $this->expire), PDO::PARAM_STR);

		return $stmt->execute();
	}

	/**
	 * Send password reset instructions to the email specified
	 *
	 * @param string $email - the email
	 *
	 * @return void
	 */
	public static function sendPasswordReset($email) {
		$user = static::findByEmail($email);

		if($user) {
			if($user->startPasswordReset()) {
				$user->sendPasswordResetEmail();
			}
		}
	}

	/**
	 * Start the password reset process by generating a new token and reset expiry
	 *
	 * @return boolean
	 */
	protected function startPasswordReset() {
		$token = new Token();
		$this->password_reset_token = $token->getValue();

		$expires = time() + 60 * 60 * 2; // 2 hours

		$sql = "
			UPDATE
				`users`
			SET
				`password_reset_hash`   = :hash,
				`password_reset_expiry` = :expires
			WHERE
				`id` = :id;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':hash', $token->getHash(), PDO::PARAM_STR);
		$stmt->bindValue(':expires', date('Y-m-d H:i:s', $expires), PDO::PARAM_STR);
		$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

		return $stmt->execute();
	}

	/**
	 * Send password reset email to the user
	 *
	 * @return void
	 */
	protected function sendPasswordResetEmail() {
		$prefix = Utilities::isSSL() ? 'https://' : 'http://';
		$url    = $prefix . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;
		$text   = View::getTemplate('Password/reset-email.txt',  ['url' => $url]);
		$html   = View::getTemplate('Password/reset-email.twig', ['url' => $url]);

		Mail::send($this->email, 'Password reset', $text, $html);
	}

	/**
	 * Find a user by password reset token
	 *
	 * @param string $token - password reset token sent to user
	 *
	 * @return mixed - user object if found and token isn't expired, else false
	 */
	public static function findByPasswordReset($token) {
		$token = new Token($token);

		$sql = "
			SELECT
				`id`,
				`username`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`,
				`role`
			FROM
				`users`
			WHERE
				`password_reset_hash` = :hash;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':hash', $token->getHash(), PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();

		if($user = $stmt->fetch()) {
			if(strtotime($user->password_reset_expiry) > time()) {
				return $user;
			}
		}

		return false;
	}

	/**
	 * Reset the user's password
	 *
	 * @param string $password - the new password
	 *
	 * @return boolean - true if the update was successful, false if not
	 */
	public function resetPassword($password) {
		$this->password = $password;

		$this->terms = true;
		$this->validate();

		if(empty($this->errors)) {
			$hash = password_hash($this->password, PASSWORD_DEFAULT);

			$sql = "
				UPDATE
					`users`
				SET
					`password`              = :hash,
					`password_reset_hash`   = NULL,
					`password_reset_expiry` = NULL
				WHERE
					`id` = :id;
			";

			$db   = static::getDB();
			$stmt = $db->prepare($sql);

			$stmt->bindValue(':hash', $hash, PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

			return $stmt->execute();
		}

		return false;
	}

	/**
	 * Send an account activation email to the user
	 *
	 * @return boolean - true if mail sent, false if not
	 */
	public function sendActivationEmail() {
		$prefix = Utilities::isSSL() ? 'https://' : 'http://';
		$url    = $prefix . $_SERVER['HTTP_HOST'] . '/register/activate/' . $this->activation_token;
		$text   = View::getTemplate('Register/activation-email.txt',  ['url' => $url]);
		$html   = View::getTemplate('Register/activation-email.twig', ['url' => $url]);

		return Mail::send($this->email, 'Account activation', $text, $html);
	}

	/**
	 * Activate the new user account with the token
	 *
	 * @param string $value - activation token from the URL
	 *
	 * @return integer - 1 activation successful, 0 if not
	 */
	public static function activate($value) {
		$token = new Token($value);

		$sql = "
			UPDATE
				`users`
			SET
				`is_active`       = 1,
				`activation_hash` = NULL,
				`resend_token`    = NULL
			WHERE
				`activation_hash` = :hash;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':hash', $token->getHash(), PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->rowCount();
	}

	/**
	 * Update a user's activation hash for resending activation emails
	 *
	 * @return boolean - true if the update succeeded, false if not
	 */
	public function updateActivationHash() {
		$token = new Token();

		$this->activation_token = $token->getValue();
		$this->activation_hash  = $token->getHash();

		$sql = "
			UPDATE
				`users`
			SET
				`activation_hash` = :hash
			WHERE
				`resend_token` = :resend_token;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':hash', $this->activation_hash, PDO::PARAM_STR);
		$stmt->bindValue(':resend_token', $this->resend_token, PDO::PARAM_STR);

		return $stmt->execute();
	}

	/**
	 * Find a user by resend activation email token
	 *
	 * @param string $token - the resend_token
	 *
	 * @return mixed - user object if user was found, false if not
	 */
	public function findByResendToken($token) {
		$sql = "
			SELECT
				`id`,
				`username`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`,
				`role`
			FROM
				`users`
			WHERE
				`resend_token` = :token;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':token', $token, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();

		if($user = $stmt->fetch()) {
			return $user;
		}

		return false;
	}

	public static function like(int $user_id, int $game_id) : bool {
		$date = Utilities::mysqlDate();

		$sql = "
			INSERT INTO
				`likes` (
					`user_id`,
					`game_id`,
					`date`
				)
			VALUES (
				:user_id,
				:game_id,
				:date
			);
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);
		$stmt->bindValue(':date', $date, PDO::PARAM_STR);

		return $stmt->execute();
	}

	public static function unlike(int $user_id, int $game_id) : bool {
		$sql = "
			DELETE FROM
				`likes`
			WHERE
				`user_id` = :user_id
			AND
				`game_id` = :game_id;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);

		return $stmt->execute();
	}

	public static function verify(int $uid, string $token, string $username) {
		$url  = "https://www.lexaloffle.com/bbs/?uid=" . $uid . '&mode=about';
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($curl);
		curl_close($curl);

		libxml_use_internal_errors(true);

		$dom = new \DOMDocument;
		$dom->loadHTML($output);

		$finder  = new \DomXPath($dom);
		$current = @$finder->query('//div[@id="main_div"]')->item(0);

		if($current) {
			$content     = $current->childNodes->item(1);
			$profile     = $content->childNodes->item(0);
			$user_div    = $profile->childNodes->item(0);
			$user_h1     = $user_div->childNodes->item(1);
			$bbs_name    = ltrim($user_h1->nodeValue, '@');
			$bbs_token   = strpos($profile->nodeValue, $token) !== false;
			$name_match  = strtolower($username) === strtolower($bbs_name);
			$verified    = $name_match && $bbs_token;

			if($verified) {
				$sql = "
					UPDATE
						`users`
					SET
						`is_verified` = 1
					WHERE
						`username` = :username;
				";

				$db   = static::getDB();
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':username', $username, PDO::PARAM_STR);
				$stmt->execute();

				return true;
			}

			return false;
		} else {
			throw new \Exception("Can't find main profile div on BBS.");
		}
	}

	public static function getAuthorAsUser(string $username) {
		$sql = "
			SELECT
				`id`,
				`name`,
				`username`,
				`registered_date`,
				`is_verified`,
				`country`,
				`bio`,
				`website`,
				`facebook`,
				`twitter`,
				`instagram`,
				`linkedin`,
				`patreon`
			FROM
				`users`
			WHERE
				`username` = :username
			LIMIT 1;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':username', $username, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}
