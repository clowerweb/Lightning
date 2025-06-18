<?php
/**
 * Utilities Helper for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

use DateTime;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use Countable;

class Utilities {
    /**
     * Get the domain with prefix
     *
     * @return string - the domain
     */
    public static function getDomain(): string {
        return (self::isSSL() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
    }

    /**
     * Get the URI without the domain (example: "/about")
     *
     * @return string - the URI
     */
    public static function getURI(): string {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get the full URL
     *
     * @return string - the URI
     */
    public static function getURL(): string {
        return (self::isSSL() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * Gets the absolute path to the root directory of the site (NOT public root; example: /var/www/site.com)
     *
     * @return string - the path to the root directory
     */
    public static function getAbsRoot(): string {
        return dirname(__DIR__, 2);
    }

    /**
     * Checks if the server is using SSL. Useful for generating URLs or limiting areas of the site to https-only
     *
     * @return boolean - true if it is, false if not
     */
    public static function isSSL(): bool {
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
    public static function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Format any kind of date string to MySQL datetime format, or return current datetime
     *
     * @param string $date - optional date to convert (empty string returns current datetime)
     *
     * @return string - MySQL compatible datetime string (Y-m-d H:i:s)
     * @throws InvalidArgumentException if the date string cannot be parsed
     */
    public static function mysqlDate(string $date = ''): string {
        if (empty($date)) {
            return date('Y-m-d H:i:s');
        }

        // Try DateTime first for better format support
        try {
            $dt = new DateTime($date);
            return $dt->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            // Fallback to strtotime for edge cases
            $timestamp = strtotime($date);

            if ($timestamp === false) {
                throw new InvalidArgumentException("Invalid date format: '$date' " . $e);
            }

            return date('Y-m-d H:i:s', $timestamp);
        }
    }

    /**
     * Convert a timestamp to a timezone and format
     *
     * @param string $date - the date to convert (from UTC/GMT)
     * @param string $timezone - the timezone to convert to
     * @param string $format - the date format
     *
     * @throws Exception from DateTime
     * @throws InvalidArgumentException if the date string cannot be parsed
     *
     * @return string - the converted date
     */
    public static function convertDate(string $date, string $timezone = '', string $format = ''): string {
        $timezone = strlen($timezone) ? $timezone : 'UTC';
        $format   = strlen($format)   ? $format   : 'Y-m-d H:i:s';

        // Use mysqlDate() for better date parsing, then convert timezone
        $normalizedDate = self::mysqlDate($date);
        $convert_time = new DateTime($normalizedDate);
        $new_timezone = new DateTimeZone($timezone);
        $convert_time->setTimeZone($new_timezone);
        return $convert_time->format($format);
    }

    /**
     * Redirect to a different page
     *
     * @param string $url  - The URL to redirect to
     * @param int    $code - Optional. The HTTP code. Defaults to 303 "See Other"
     *
     * @return void
     */
    public static function redirect(string $url, int $code = 301): null {
        $prefix = Utilities::isSSL() ? 'https://' : 'http://';
        header('Location: ' . $prefix . $_SERVER['HTTP_HOST'] . $url, true, $code);
        exit;
    }

    /**
     * Check if something is falsey (empty, blank, null, false, etc.).
     * Useful for making sure just about anything has a value
     *
     * @param mixed $item - the item to check
     *
     * @throws Exception - if we haven't handled the type
     *
     * @return bool - true if the item is empty, false if not
     */
    public static function isFalsey(mixed $item): bool {
        if (is_null($item)) {
            return true;
        }

        if (is_bool($item)) {
            return !$item;
        }

        if (is_string($item)) {
            return trim($item) === '';
        }

        if (is_numeric($item)) {
            return false;
        }

        if (is_array($item)) {
            return empty($item);
        }

        if (is_object($item)) {
            // Handle Countable objects (like collections)
            if ($item instanceof Countable) {
                return count($item) === 0;
            }

            // Check if object has a length property
            if (property_exists($item, 'length')) {
                return (int)$item->length === 0;
            }

            // Fall back to checking if object has any public properties
            return empty(get_object_vars($item));
        }

        // Handle resources
        if (is_resource($item)) {
            return false; // Resources are generally considered "truthy" when they exist
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
    public static function isAlpha(string $val): bool {
        return (bool)preg_match("/^[\p{L} ]*$/u", $val);
    }

    /**
     * Check if a value contains only numbers (no letters or special characters)
     *
     * @param int $val - the value to check
     *
     * @return boolean - true if the value is numeric, false if not
     */
    public static function isNumeric(mixed $val): bool {
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
    public static function isAlphanumeric(string $val, string $special = ''): bool {
        return (bool)preg_match("/^[\p{L}\d $special]*$/u", $val);
    }

    /**
     * Check if a value is a URL
     *
     * @param string $val - the value to check
     *
     * @return boolean - true if the value is a URL, false if not
     */
    public static function isURL(string $val): bool {
        return filter_var($val, FILTER_VALIDATE_URL);
    }

    /**
     * Check if a value is a valid email address
     *
     * @param string $val - the value to check
     *
     * @return boolean - true if the value is a valid email, false if not
     */
    public static function isEmail(string $val): bool {
        return filter_var($val, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Converts a string to different cases.
     *
     * @param string $string The string to convert
     * @param string $case The case to convert to. Supported cases are:
     *                     - 'studly' (or 'PascalCase'): converts to StudlyCaps, e.g. post-authors becomes PostAuthors
     *                     - 'camel' (or 'camelCase'): converts to camelCase, e.g. post-authors becomes postAuthors
     *                     - 'title' (or 'Title Case'): converts to title case, e.g. post-authors becomes Post-Authors
     *                     - 'snake' (or 'snake_case'): converts to snake case, e.g. post-authors becomes post_authors
     *                     - 'kebab' (or 'kebab-case'): converts to kebab case, e.g. post-authors becomes post-authors
     *
     * @return string|null The converted string, or null if the case is not supported
     */
    public static function strToCase(string $string, string $case): ?string {
        return match ($case) {
            'studly' => self::convertToStudlyCaps($string),
            'camel'  => self::convertToCamelCase($string),
            'title'  => self::convertToTitleCase($string),
            'snake'  => self::convertToCase($string),
            'kebab'  => self::convertToCase($string, '-'),
            default  => null,
        };
    }

    /**
     * Convert the string with hyphens to StudlyCaps, e.g. post-authors
     * becomes PostAuthors.
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    public static function convertToStudlyCaps(string $string): string {
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
     * Convert a string to snake_case. Handles various input formats including
     * camelCase, PascalCase, kebab-case, and space-separated words.
     *
     * @param string $string - The string to convert
     * @param string $separator - The separator to use ('_' for snake_case, '-' for kebab-case)
     *
     * @return string
     */
    public static function convertToCase(string $string, string $separator = '_'): string {
        // First, handle camelCase and PascalCase by inserting separators before uppercase letters
        $string = preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $string);

        // Replace hyphens, underscores, spaces, and other non-alphanumeric characters with the separator
        $string = preg_replace('/[^a-zA-Z0-9]+/', $separator, $string);

        // Convert to lowercase
        $string = strtolower($string);

        // Remove leading/trailing separators and collapse multiple separators
        $string = trim($string, $separator);

        return preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $string);
    }

    /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table.
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string variables removed
     */
    public static function removeQueryStringVars(string $url): string {
        if($url != '') {
            $parts = explode('&', $url, 2);

            if(!str_contains($parts[0], '=')) {
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
    public static function stringCleanup(string $string): string {
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
        return preg_replace('/\s+/', ' ', $string);
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
    public static function removeLongWords(string $string, int $len): string {
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

        // lowercase and return
        return strtolower($text);
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
        $len = abs($len);
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
        return preg_replace_callback("#(<a[^>]+?)>#i",
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
