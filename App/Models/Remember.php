<?php

namespace App\Models;

use PDO;
use \Core\Model;
use \App\Token;

/**
 * Remember model
 *
 * PHP version 7.2
 */
class Remember extends Model {
	private $user_id;
	private $expires_date;
	private $token_hash;

	/**
	 * Find a remembered login by the token
	 *
	 * @param string $token - the login token
	 *
	 * @return mixed - the login object if found, false if not
	 */
	public static function findByToken($token) {
		$token = new Token($token);
		$hash  = $token->getHash();

		$sql = "
			SELECT
				`token_hash`,
				`user_id`,
				`expires_date`
			FROM
				`remembered_logins`
			WHERE
				`token_hash` = :token;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':token', $hash, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();

		return $stmt->fetch();
	}

	/**
	 * Get the user object for this remembered login
	 *
	 * @return object - the user object
	 */
	public function getUser() {
		return User::findByID($this->user_id);
	}

	/**
	 * Check if the remember token has expired or not in the db
	 *
	 * @return boolean - true if it's expired, false if not
	 */
	public function hasExpired() {
		return strtotime($this->expires_date) < time();
	}

	/**
	 * Delete a remembered login from the database remembered_logins table
	 *
	 * @return void
	 */
	public function delete() {
		$sql = "
			DELETE FROM
				`remembered_logins`
			WHERE
				`token_hash` = :token;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':token', $this->token_hash, PDO::PARAM_STR);
		$stmt->execute();
	}
}
