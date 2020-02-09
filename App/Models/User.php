<?php

namespace App\Models;

use \PDO;
use \Exception;
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
    public $username;
    public $password;
    public $email;
    public $terms;
    public $resend_token;
    public $token;
    public $expire;
    public $registered_date;
    public $name;
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
    public function __construct(array $data = []) {
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
				`email`,
				`is_active`,
				`registered_date`
			FROM
				`users`;
		");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Save a new user to the users table
     *
     * @throws Exception
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
						`email`,
						`password`,
						`activation_hash`,
						`resend_token`,
						`registered_date`
					)
				VALUES (
					:email,
					:password,
					:hash,
					:resend_token,
					:registered_date
				);
			";

            $db   = static::getDB();
            $stmt = $db->prepare($sql);

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
				`password`  = :password
			WHERE
				`id` = :user_id
		";

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name',      $data['name'],      PDO::PARAM_STR);
        $stmt->bindValue(':email',     $data['email'],     PDO::PARAM_STR);
        $stmt->bindValue(':password',  $data['password'],  PDO::PARAM_STR);
        $stmt->bindValue(':user_id',   $user_id,           PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Validate the values submitted by the user and add errors to the errors array
     *
     * @return void
     */
    public function validate() : void {
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
    }

    /**
     * Make sure the email is unique
     *
     * @param string $email - the email address to check
     * @param boolean $ignore - return false if the ID matches the user ID (to make our validate method work for updates)
     *
     * @return boolean - true if record exists, false if it's unique
     */
    public static function emailExists(string $email, bool $ignore = null) {
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
    public static function findByEmail(string $email) {
        $sql = "
			SELECT
				`id`,
				`name`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`,
				`registered_date`
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
    public static function authenticate(string $email, string $password) {
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
    public static function findByID(int $id) {
        $sql = "
			SELECT
				`id`,
				`name`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`,
				`registered_date`
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
     * @throws Exception
     *
     * @return boolean - true if the login was remembered, false if not
     */
    public function rememberLogin() : bool {
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
    public static function sendPasswordReset(string $email) {
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
     * @throws Exception
     *
     * @return boolean
     */
    protected function startPasswordReset() : bool {
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
     * @throws Exception
     *
     * @return void
     */
    protected function sendPasswordResetEmail() : void {
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
     * @throws Exception
     *
     * @return mixed - user object if found and token isn't expired, else false
     */
    public static function findByPasswordReset(string $token) {
        $token = new Token($token);

        $sql = "
			SELECT
				`id`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`
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
    public function resetPassword(string $password) : bool {
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
     * @throws Exception
     *
     * @return boolean - true if mail sent, false if not
     */
    public function sendActivationEmail() : bool {
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
     * @throws Exception
     *
     * @return integer - 1 activation successful, 0 if not
     */
    public static function activate(string $value) : int {
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
     * @throws Exception
     *
     * @return boolean - true if the update succeeded, false if not
     */
    public function updateActivationHash() : bool {
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
    public function findByResendToken(string $token) {
        $sql = "
			SELECT
				`id`,
				`email`,
				`password`,
				`password_reset_hash`,
				`password_reset_expiry`,
				`activation_hash`,
				`resend_token`,
				`is_active`
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
}
