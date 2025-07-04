<?php
/**
 * Error Helper for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */

declare(strict_types = 1);

namespace Lightning\Helpers;

use Exception;
use ErrorException;

class Error {
	/**
	 * Custom error handler
	 *
	 * @param int    $level   - the error level
	 * @param string $message - the message to log
	 * @param string $file    - the file that threw the error
	 * @param int    $line    - the line in the file
	 *
	 * @throws ErrorException
	 *
	 * @return void
	 */
	public static function errorHandler(int $level, string $message, string $file, int $line): void {
		if(error_reporting() !== 0) {
			throw new ErrorException($message, 0, $level, $file, $line);
		}
	}

	/**
	 * Custom exception handler
	 *
	 * @param object $exception - the exception
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public static function exceptionHandler(object $exception): void {
		$code = $exception->getCode();

		if($code !== 404) {
			$code = 500;
		}

		http_response_code($code);

		if(strtolower($_ENV['ENVIRONMENT']) !== 'prod') {
			echo '<h1>Fatal error</h1>';
			echo '<p>Uncaught exception: "' . get_class($exception) . '"</p>';
			echo '<p>Message: "' . $exception->getMessage() . '"</p>';
			echo '<p>Stack trace: <pre>' . static::getFullException($exception) . '</pre></p>';
			echo '<p>Thrown in "' . $exception->getFile() . '" on line ' . $exception->getLine() . '</p>';
        }

        static::logErrors($exception);
    }

	/**
	 * Gets the full exception
	 *
	 * @param object $exception - the exception
	 *
	 * @return string - the message
	 */
	private static function getFullException(object $exception): string {
		$result = '';
		$errors = $exception->getTrace();

		for($i = 0; $i < count($errors); $i++) {
			$error    = $errors[$i];
			$args_str = NULL;
			$file     = $error['file']     ?? NULL;
			$line     = $error['line']     ?? NULL;
			$function = $error['function'] ?? NULL;

			if(isset($error['args'])) {
				$args = array();

				foreach($error['args'] as $arg) {
					if    (is_array($arg))    $args[] = 'Array';
					elseif(is_bool($arg))     $args[] = ($arg) ? 'true' : 'false';
					elseif(is_null($arg))     $args[] = 'NULL';
					elseif(is_object($arg))   $args[] = get_class($arg);
					elseif(is_resource($arg)) $args[] = get_resource_type($arg);
					elseif(is_string($arg))   $args[] = "'$arg'";
					else                      $args[] = $arg;
				}

				$args_str = implode(', ', $args);
			}

			$result .= sprintf("#%s %s(%s): %s(%s)\r\n", $i, $file, $line, $function, $args_str);
		}

		return $result;
	}

    /**
     * Logs the errors
     *
     * @param object $exception - the exception
     *
     * @return void
     */
	private static function logErrors(object $exception): void {
        $dir = dirname(__DIR__) . '/logs';
        $log = $dir . '/' . date('Y-m-d') . '.txt';

        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        ini_set('error_log', $log);

        $message  = 'Uncaught exception: "' . get_class($exception) . '"';
        $message .= ' with message "'   . $exception->getMessage() . '"';
        $message .= "\r\nStack trace: " . static::getFullException($exception);
        $message .= "\r\nThrown in \""  . $exception->getFile() . "\" on line " . $exception->getLine();

        error_log($message . "\r\n\r\n");
    }
}
