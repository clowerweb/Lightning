<?php

namespace Core;

use \PDO;
use App\Config;

/**
 * Model class
 *
 * PHP version 7.0
 */
abstract class Model {
	protected static function getDB() {
		static $db = null;

		if($db === null) {
			$dsn  = 'mysql:host=' . Config::DB_HOST;
			$dsn .= ';dbname='    . Config::DB_NAME . ';';
			$db   = new PDO($dsn, Config::DB_USER, Config::DB_PASS);

			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return $db;
	}
}
