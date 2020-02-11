<?php

namespace App\Models;

use \PDO;
use Core\Model;

/**
 * Users model
 *
 * PHP version 7.2
 */
class Users extends Model {

	/**
	 * Constructor
	 *
	 * @param array $data - the initial property values
	 */
	public function __construct(array $data = []) {
		foreach ($data as $key => $val) {
			$this->$key = $val;
		}
	}

	/**
	 * Get all users as an associative array
	 *
	 * @return array
	 */
	public static function getAll(): array {
		$db   = static::getDB();
		$stmt = $db->query("
			SELECT
				u.`id`,
				u.`name`,
				u.`email`,
				u.`is_active`,
				u.`registered_date`,
				r.`id` AS role_id,
				r.`name` AS role
			FROM
				`users` AS u
			JOIN
				`roles` as r
			ON
			    r.`id` = u.`role`;
		");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}