<?php

namespace App\Models;

use PDO;
use Core\Model;

/**
 * Settings model
 *
 * PHP version 7.2
 */
class Settings extends Model {
	/**
	 * Get all the site settings from the database
	 *
	 * @return array - associative array of all settings
	 */
	public static function getSettings() {
		$sql = "
			SELECT
				`site_name`,
				`site_tagline`,
				`allow_registration`,
				`require_activation`,
				`require_approval`,
				`default_timezone`
			FROM
				`settings`
			LIMIT 1;
		";

		$db   = static::getDB();
		$stmt = $db->query($sql);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Save the site settings
	 *
	 * @param array $data - $_POST data, passed from the settings controller
	 *
	 * @return bool
	 */
	public static function saveSettings($data) {
		$allow_registration = isset($data['allow_registration']);
		$require_activation = isset($data['require_activation']);
		$require_approval   = isset($data['require_approval']);

		$sql = "
			UPDATE
				`settings`
			SET
				`site_name`          = :site_name,
				`site_tagline`       = :site_tagline,
				`allow_registration` = :allow_registration,
				`require_activation` = :require_activation,
				`require_approval`   = :require_approval,
				`default_timezone`   = :default_timezone
			WHERE
				`id` = 1;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':site_name',          $data['site_name'],        PDO::PARAM_STR);
		$stmt->bindValue(':site_tagline',       $data['site_tagline'],     PDO::PARAM_STR);
		$stmt->bindValue(':allow_registration', $allow_registration,       PDO::PARAM_BOOL);
		$stmt->bindValue(':require_activation', $require_activation,       PDO::PARAM_BOOL);
		$stmt->bindValue(':require_approval',   $require_approval,         PDO::PARAM_BOOL);
		$stmt->bindValue(':default_timezone',   $data['default_timezone'], PDO::PARAM_STR);

		return $stmt->execute();
	}
}
