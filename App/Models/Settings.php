<?php

namespace App\Models;

use PDO;
use \Core\Model;

/**
 * Settings model
 *
 * PHP version 7.0
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
				`site_theme`,
				`allow_registration`,
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
		$allow_registration = isset($data['allow_registration']) ? 1 : 0;

		$sql = "
			UPDATE
				`settings`
			SET
				`site_name`          = :site_name,
				`site_tagline`       = :site_tagline,
				`site_theme`         = :site_theme,
				`allow_registration` = :allow_registration,
				`default_timezone`   = :default_timezone
			WHERE
				`id` = 1;
		";

		$db   = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':site_name',          $data['site_name'],        PDO::PARAM_STR);
		$stmt->bindValue(':site_tagline',       $data['site_tagline'],     PDO::PARAM_STR);
		$stmt->bindValue(':site_theme',         $data['site_theme'],       PDO::PARAM_STR);
		$stmt->bindValue(':allow_registration', $allow_registration,       PDO::PARAM_INT);
		$stmt->bindValue(':default_timezone',   $data['default_timezone'], PDO::PARAM_STR);

		return $stmt->execute();
	}
}