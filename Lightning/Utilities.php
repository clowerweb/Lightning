<?php

namespace Lightning;

/**
 * Utilities class for Lightning 3
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
class Utilities {
    /**
     * Get the domain with prefix
     *
     * @return string - the domain
     */
    public static function getDomain() : string {
        return (self::isSSL() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
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
        return (self::isSSL() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
     * @throws Exception from DateTime
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
    public static function isNumeric(mixed $val) : bool {
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
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table.
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string variables removed
     */
    public static function removeQueryStringVars(string $url) : string {
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
}
