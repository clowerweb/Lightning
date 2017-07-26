<?php

namespace Core;

/**
 * Utilities class
 *
 * PHP version 7.0
 */
class Utilities {
	/**
	 * Get the URI
	 *
	 * @return string - the URI
	 */
	public static function getURI() {
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Checks if the server is using SSL
	 *
	 * @return boolean - true if it is, false if not
	 */
	public static function isSSL() {
		if(isset($_SERVER['HTTPS'])) {
			$https = strtolower($_SERVER['HTTPS']);

			if($https == 'on' || $https == '1') {
				return true;
			}
		} else if(isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == '443')) {
			return true;
		}

		return false;
	}

	/**
	 * Checks whether a request was made via AJAX
	 *
	 * @return boolean - true if it is, false if not
	 */
	public static function isAjax() {
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

	/**
	 * Format any kind of date string to mysql, or just return a mysql compatible date
	 *
	 * @param bool|string $date - optional date to convert
	 *
	 * @return string - the date
	 */
	public static function mysqlDate($date = false) {
		if($date) return date("Y-m-d H:i:s", strtotime($date));

		return date("Y-m-d H:i:s");
	}

	/**
	 * Check if a string or array is not empty or blank
	 *
	 * @param mixed $item - the array or string to check
	 *
	 * @return boolean - true if the item is not empty, false if not
	 */
	public static function valueSet($item) {
		if(is_array($item)) {
			return !empty($item);
		}

		return strlen(trim($item)) ? true : false;
	}

	/**
	 * Check if a value contains only letters
	 *
	 * @param string $val - the value to check
	 *
	 * @return boolean - true if the value is alpha, false if not
	 */
	public static function isAlpha($val) {
		return preg_match("/^[\p{L} ]*$/u", $val);
	}

	/**
	 * Check if a value contains only numbers
	 *
	 * @param string $val - the value to check
	 *
	 * @return boolean - true if the value is numeric, false if not
	 */
	public static function isNumeric($val) {
		return is_numeric($val);
	}

	/** Check if a value is alphanumeric (no special chars)
	 *
	 * @param string $val - the value to check
	 *
	 * @return boolean - true if the value is alphanumeric, false if not
	 */
	public static function isAlphanumeric($val) {
		return preg_match("/^[\p{L}\d ]*$/u", $val);
	}

	/**
	 * Convert a string with hyphens to StudlyCaps
	 *
	 * @param string $string - The string to convert
	 *
	 * @return string
	 */
	public static function convertToStudlyCaps($string) {
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	/**
	 * Convert a string with hyphens to camelCase
	 *
	 * @param string $string - The string to convert
	 *
	 * @return string
	 */
	public static function convertToCamelCase($string) {
		return lcfirst(static::convertToStudlyCaps($string));
	}

	/**
	 * Remove query string vars from a URL
	 *
	 * @param string $url - The URL to remove vars from
	 *
	 * @return string
	 */
	public static function removeQueryStringVars($url) {
		if($url != '') {
			$parts = explode('&', $url, 2);

			if(strpos($parts[0], '=') === false) {
				$url = $parts[0];
			} else {
				$url = '';
			}
		}

		return $url;
	}

	/**
	 * Convert a string into a proper slug
	 *
	 * @param string $text - the string to convert
	 * @param integer $length - the length to truncate the slug to
	 *
	 * @return mixed - the slug sting if not empty after processing, false if it is
	 */
	public static function slugify($text, $length = 75) {
		// replace ' with nothing
		$text = str_replace("'", '', $text);

		// replace " with nothing
		$text = str_replace('"', '', $text);

		// replace non-alphanumeric with -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate encoding
		$text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim dashes from front and back
		$text = trim($text, '-');

		// remove duplicate dashes
		$text = preg_replace('~-+~', '-', $text);

		// trim it down to 75 characters max without breaking words
		$text = preg_replace("/^(.{1,$length})(-.*|$)/s", '\\1', $text);

		// lowercase
		$text = strtolower($text);

		return empty($text) ? false : $text;
	}

	/**
	 * Convert a timestamp to a timezone and format
	 *
	 * @param string $date - the date to convert (from UTC/GMT)
	 * @param string $timezone - the timezone to convert to
	 * @param string $format - the date format
	 *
	 * @return string - the converted date
	 */
	public static function convertDate($date, $timezone, $format) {
		$convert_time = new \DateTime($date);
		$new_timezone = new \DateTimeZone($timezone);

		$convert_time->setTimeZone($new_timezone);

		return $convert_time->format($format);
	}
}