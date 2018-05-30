<?php

declare(strict_types = 1);

namespace Core;

use \DateTime;
use \DateTimeZone;
use \Exception;
use \HTMLPurifier;
use \HTMLPurifier_Config;
use App\Config;

/**
 * Utilities class. Has useful methods for getting/processing/validating/formatting data
 *
 * PHP version 7.2
 */
class Utilities extends Model {
	/**
	 * Get the domain with prefix
	 *
	 * @return string - the domain
	 */
	public static function getDomain() : string {
		return (static::isSSL() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
	}

	/**
	 * Get the URI without the domain (example: "/pages/about")
	 *
	 * @return string - the URI
	 */
	public static function getURI() : string {
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Get the full URL
	 *
	 * @return string - the URI
	 */
	public static function getURL() : string {
		return (static::isSSL() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	/**
	 * Gets the absolute path to the root directory of the site (NOT public root; example: /var/www/site.com)
	 *
	 * @return string - the path to the root directory
	 */
	public static function getAbsRoot() : string {
		return dirname(__DIR__);
	}

	/**
	 * Checks if the server is using SSL. Useful for generating URLs or limiting areas of the site to https-only
	 *
	 * @return boolean - true if it is, false if not
	 */
	public static function isSSL() : bool {
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
	 * Checks whether a request was made via AJAX. Useful for disallowing requests that aren't made with AJAX, such as
	 * navigating to addresses in the browser or preventing cURL or file_get_contents requests from other sites. Can also
	 * be used to serve different responses to AJAX requests (such as JSON) than non-AJAX requests
	 *
	 * @return boolean - true if it is, false if not
	 */
	public static function isAjax() : bool {
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

	/**
	 * Format any kind of date string to mysql, or just return a mysql compatible date
	 *
	 * @param string $date - optional date to convert
	 *
	 * @return string - the date
	 */
	public static function mysqlDate(string $date = '') : string {
		return strlen($date) ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d H:i:s');
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
	public static function convertDate(string $date, string $timezone = '', string $format = '') : string {
		$timezone = strlen($timezone) ? $timezone : 'UTC';
		$format   = strlen($format)   ? $format   : 'Y-m-d H:i:s';

		$convert_time = new DateTime($date);
		$new_timezone = new DateTimeZone($timezone);

		$convert_time->setTimeZone($new_timezone);

		return $convert_time->format($format);
	}

	/**
	 * Check if something is empty, blank, null, false, etc. Useful for making sure just about anything has a value
	 *
	 * @param mixed $item - the item to check
	 *
	 * @throws Exception - if we haven't handled the type
	 *
	 * @return boolean - true if the item is empty, false if not
	 */
	public static function isEmpty($item) : bool {
		if     (is_array ($item))  return empty($item);
		else if(is_null  ($item))  return true;
		else if(is_string($item))  return strlen(trim($item)) ? false : true;
		else if(is_numeric($item)) return false;
		else if(is_bool($item))    return !$item;
		else if(is_object($item)) {
			if(empty((array)$item)) return true;
			else return (int)$item->length === 0;
		}

		throw new Exception('Unhandled type: "' . gettype($item) . '"');
	}

	/**
	 * Check if a value contains only letters (no numbers or special characters)
	 *
	 * @param string $val - the value to check
	 *
	 * @return boolean - true if the value is alpha, false if not
	 */
	public static function isAlpha(string $val) : bool {
		return preg_match("/^[\p{L} ]*$/u", $val) ? true : false;
	}

	/**
	 * Check if a value contains only numbers (no letters or special characters)
	 *
	 * @param int $val - the value to check
	 *
	 * @return boolean - true if the value is numeric, false if not
	 */
	public static function isNumeric(int $val) : bool {
		return is_numeric($val);
	}

	/**
	 * Check if a value is alphanumeric (no special characters)
	 *
	 * @param string $val     - the value to check
	 * @param string $special - optional - if you want to allow any special characters, put them here
	 *
	 * @return boolean - true if the value is alphanumeric, false if not
	 */
	public static function isAlphanumeric(string $val, string $special = '') : bool {
		return preg_match("/^[\p{L}\d $special]*$/u", $val) ? true : false;
	}

	/**
	 * Check if a value is a URL
	 *
	 * @param string $val - the value to check
	 *
	 * @return boolean - true if the value is a URL, false if not
	 */
	public static function isURL(string $val) : bool {
		return filter_var($val, FILTER_VALIDATE_URL);
	}

	/**
	 * Check if a value is a valid email address
	 *
	 * @param string $val - the value to check
	 *
	 * @return boolean - true if the value is a valid email, false if not
	 */
	public static function isEmail(string $val) : bool {
		return filter_var($val, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Convert a string with hyphens to StudlyCaps. Used mostly for calling controllers from a URL.
	 *
	 * @param string $string - The string to convert
	 *
	 * @return string
	 */
	public static function convertToStudlyCaps(string $string) : string {
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	/**
	 * Convert a string with hyphens to camelCase. Used mostly for calling methods from a URL.
	 *
	 * @param string $string - The string to convert
	 *
	 * @return string
	 */
	public static function convertToCamelCase(string $string) : string {
		return lcfirst(static::convertToStudlyCaps($string));
	}

	/**
	 * Convert a string to Title Case. Can be used to convert any string to a (fairly) proper title (depending on who you ask!)
	 *
	 * @param string $string - the string to convert
	 *
	 * @return string
	 */
	public static function convertToTitleCase(string $string) : string {
		$no_caps = ['a','aboard','about','above','across','after','against','along','amid','among','an','and','anti','around','as','at','before','behind','below','beneath','beside','besides','between','beyond','but','by','concerning','considering','despite','down','during','except','excepting','excluding','following','for','from','in','inside','into','is','like','minus','near','of','off','on','onto','opposite','or','outside','over','past','per','plus','regarding','round','save','since','so','than','the','through','to','toward','towards','under','underneath','unlike','until','up','upon','versus','via','with','within','without','yet'];
		$words   = explode(' ', $string);
		$count   = count($words) - 1;

		foreach ($words as $key => $word) {
			// always capitalize the first and last words no matter what
			if ($key === 0 || !in_array($word, $no_caps) || $key === $count) {
				$words[$key] = ucwords($word);
			}
		}

		return implode(' ', $words);
	}

	/**
	 * Remove query string vars from a URL. Used mostly for figuring out controller/action/params for the clean URLs
	 *
	 * @param string $url - The URL to remove vars from
	 *
	 * @return string
	 */
	public static function removeQueryStringVars(string $url) : string {
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
	 * Clean up a string. Useful for things like excerpts or other strings that should have formatting removed. See the
	 * comments inside for more info. This is NOT for sanitizing!
	 *
	 * @param string $string - the string to clean up
	 *
	 * @return string - the cleaned string
	 */
	public static function stringCleanup(string $string) : string {
		// replace line break tags with a space
		$string = preg_replace('/(<br\s?\/?>)/i', ' ', $string);
		// replace </p> paragraph closing tags with a space
		$string = preg_replace('/(<\/p>)/i', ' ', $string);
		// replace space entities with actual spaces
		$string = str_replace('&nbsp;', ' ', $string);
		// convert things like "&nbsp;" to characters
		$string = html_entity_decode($string);
		// remove html
		$string = strip_tags($string);
		// remove spaces from front and back
		$string = trim($string);
		// find multiple spaces in a row and replace them with just one space
		$string = preg_replace('/\s+/', ' ', $string);

		return $string;
	}

	/**
	 * Finds URLs in a string and removes them. Useful for excerpts or anything you want URLs removed from. Does not remove
	 * <a> tags, but will remove the href value from them -- remove <a> tags another way. This is for removing raw URLs
	 * from a string. This is NOT for sanitizing!
	 *
	 * @param string $string - the string to remove URLs from
	 *
	 * @return string - the cleaned string
	 */
	public static function removeURLs(string $string) : string {
		return preg_replace('/(https?:\/\/([-\w\.]+[-\w])+(:\d+)?(\/([\w\/_\.#-]*(\?\S+)?[^\.\s])?))/i', '', $string);
	}

	/**
	 * Finds long words in a string and removes them entirely. Useful for when you want to trim off long words and keep
	 * them from overflowing in the front end, or taking up too much space on their own.
	 *
	 * @param string $string - the string to remove long words from
	 * @param int    $len    - min length of words to remove
	 *
	 * @return string - the cleaned string
	 */
	public static function removeLongWords(string $string, int $len) : string {
		return preg_replace("/\S{,$len}/", '', $string);
	}

	/**
	 * Convert a string into a proper slug. It will convert practically any string to a safe and readable URI slug.
	 *
	 * @param string  $text   - the string to convert
	 * @param integer $length - the length to truncate the slug to
	 *
	 * @throws Exception
	 *
	 * @return string - the slug
	 */
	public static function slugify(string $text, int $length = 75) : string {
		// replace ' with nothing
		$text = str_replace("'", '', $text);
		// replace " with nothing
		$text = str_replace('"', '', $text);
		// replace non-alphanumeric with -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		// transliterate encoding (replaces non-English characters with the closest looking English ones)
		$text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
		// remove unwanted characters, such as symbols or other weird characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		// trim dashes from front and back
		$text = trim($text, '-');
		// remove duplicate dashes
		$text = preg_replace('~-+~', '-', $text);
		// trim it down to $length characters max without breaking words
		$text = static::truncate($text, $length, false, '', false, '-');
		// lowercase
		$text = strtolower($text);

		return $text;
	}

	/**
	 * Truncate a string to a specified length. Can do it with or without breaking words (while preserving the max length),
	 * and adding your own optional end to anything that's been truncated. Can also optionally count the length of the
	 * ending towards the limit.
	 *
	 * @param string $str          - the string to truncate
	 * @param int    $len          - the max length of the string
	 * @param bool   $count_ending - should the $ending count against $len?
	 * @param string $ending       - something to append to the end of the string (default '...')
	 * @param bool   $break_words  - if false, it will truncate down to $len without breaking words (default true)
	 * @param string $delimiter    - the delimiter it will use to determine where words start/end (default '\s' (space))
	 *
	 * @throws Exception - if the truncated string would be less than 0 characters
	 *
	 * @return string - the truncated string
	 */
	public static function truncate(string $str, int $len, bool $count_ending = false, string $ending = '...', bool $break_words = true, string $delimiter = '\s') : string {
		// we always want to strip html out of truncated strings, otherwise we might get broken or unclosed html
		$str = trim(strip_tags(html_entity_decode($str)));
		$len = abs((int)$len);
		$len = $count_ending ? $len - strlen($ending) : $len;

		if($len < 0) {
			throw new Exception('Tried to truncate a string to less than 0 characters.');
		}

		// see if $str is actually longer than $len first
		if(strlen($str) > $len) {
			// if breaking words, simply return the substr with the ending appended
			if($break_words) {
				return substr($str, 0, $len) . $ending;
			}

			// truncate it down as close to $len as possible without going over or breaking words
			$str = preg_replace("/^(.{1,$len})($delimiter.*|$)/s", "\\1$ending", $str);

			// if $str > $len then truncate it again (this is a bug from the above line if $len is shorter than
			// the first word in $str, so in this case now we HAVE to break the word)
			return strlen($str) > $len ? substr($str, 0, $len) . $ending : $str;
		}

		return $str;
	}

	/**
	 * Adds rel="nofollow noopener noreferrer" and target="_blank" to external links. You can feed an entire html doc in
	 * if you want, and it will find all the links and add this to them. Removes existing rel and target attributes first
	 *
	 * @param string $html - the html to parse
	 *
	 * @return string
	 */
	public static function externalLinks(string $html) : string {
		return preg_replace_callback("#(<a[^>]+?)>#is",
			function($match) {
				// get the contents of the href attribute
				preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $match[0], $res);
				$href = $res['href'][0];

				// check and see if the url is ours or external
				$ours = preg_match("/^(https?:\/\/(w{3}.)?($_SERVER[HTTP_HOST]))/i", $href);
				// it's an internal page if it doesn't start with http:// or https://
				$page = !preg_match("/^(https?:)\/\//i", $href);

				// if it's not ours and not an internal page, append the rel and target attributes
				if(!$ours && !$page) {
					// remove existing rel attribute
					$link = preg_replace('/(<[^>]+) rel=".*?"/i', '$1', $match[1]);
					// remove existing target attribute
					$link = preg_replace('/(<[^>]+) target=".*?"/i', '$1', $link);
					// append our stuff to it
					$link .= ' rel="nofollow noopener noreferrer" target="_blank">';

					return $link;
				}

				return $match[0];
			}, $html
		);
	}

	/**
	 * Checks if a directory is empty
	 *
	 * @param string $dir - the directory to scan
	 *
	 * @return bool - true if empty, false if not
	 */
	public static function dirIsEmpty(string $dir) : bool {
		foreach(scandir($dir) as $file) {
			if(!in_array($file, ['.', '..'])) return false;
		}

		return true;
	}

	/**
	 * !!! DANGER, WILL ROBINSON !!!
	 * Deletes all files and folders within a directory recursively. Careful with this one! Useful for things like
	 * clearing out your Twig cache folder, purging just about any other folder you want, accidentally deleting your
	 * entire website, or wiping your entire hard drive. Seriously DO NOT USE THIS IF YOU DON'T KNOW WHAT YOU'RE DOING,
	 * AND ALWAYS BACK EVERYTHING UP BEFORE YOU PLAY WITH THIS METHOD!!!
	 *
	 * @param string $dir - the path to the directory to delete (absolute is best, use Utilities::getAbsRoot() to start)
	 *
	 * @return void
	 */
	public static function emptyFolder(string $dir) {
		$files = array_diff(scandir($dir), array('.', '..'));

		foreach ($files as $file) {
			$item = "$dir/$file";

			if(is_dir($item)) {
				if(!static::dirIsEmpty($item)) {
					static::emptyFolder($item);
				}

				rmdir($item);
			} else {
				unlink($item);
			}
		}
	}

	/**
	 * Purges the template cache (directory defined in App\Config.php)
	 *
	 * @return void
	 */
	public static function purgeTemplateCache() {
		$dir = static::getAbsRoot() . Config::CACHE_DIRECTORY;

		static::emptyFolder($dir);
	}

	/**
	 * Uses HTML Purifier to prevent XSS
	 * Useful if you want to add a front-end WYSIWYG editor for site visitors to use
	 * This should be placed on OUTPUT (such as with {{ twig.output|raw }})
	 *
	 * @param string $html - the HTML to purify
	 *
	 * @return string - the purified HTML
	 */
	public static function purifyOutput(string $html) : string {
		$config   = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);

		return $purifier->purify($html);
	}
}
