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
				`id`,
				`name`,
				`email`,
				`role`,
				`is_active`,
				`registered_date`
			FROM
				`users`;
		");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}