<?php

namespace App\Models;

use \PDO;
use Core\Model;

/**
 * Roles model
 *
 * PHP version 7.2
 */
class Roles extends Model {
	public static function getRoles() {
		$sql = "
			SELECT
				`id`,
				`name`
			FROM
				`roles`;
		";

		$db   = static::getDB();
		$stmt = $db->query($sql);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function update(int $user_id, int $role_id) {
		$sql = "
			UPDATE
				`users`
			SET
				`role` = :role
			WHERE
				`users`.id = :id;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':id',   $user_id, PDO::PARAM_INT);
		$stmt->bindValue(':role', $role_id, PDO::PARAM_INT);

		return $stmt->execute();
	}
}
