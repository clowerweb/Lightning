<?php

namespace Core;

use App\Config;

/**
 * Error class
 *
 * PHP version 7.0
 */
class Error {
	public static function errorHandler($level, $message, $file, $line) {
		if(error_reporting() !== 0) {
			throw new \ErrorException($message, 0, $level, $file, $line);
		}
	}

	public static function exceptionHandler($exception) {
		$code = $exception->getCode();

		if($code !== 404) {
			$code = 500;
		}

		http_response_code($code);

		if(Config::SHOW_ERRORS) {
			echo '<h1>Fatal error</h1>';
			echo '<p>Uncaught exception: "' . get_class($exception) . '"</p>';
			echo '<p>Message: "' . $exception->getMessage() . '"</p>';
			echo '<p>Stack trace: <pre>' . static::getFullException($exception) . '</pre></p>';
			echo '<p>Thrown in "' . $exception->getFile() . '" on line ' . $exception->getLine() . '</p>';
		} else {
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
			View::renderTemplate("$code.twig");
		}
	}
	
	private static function getFullException($exception) {
		$error = '';
		$count = 0;

		foreach($exception->getTrace() as $frame) {
			$args = '';

			if(isset($frame['args'])) {
				$args = array();

				foreach($frame['args'] as $arg) {
					if    (is_string($arg))   $args[] = "'$arg'";
					elseif(is_array($arg))    $args[] = 'Array';
					elseif(is_null($arg))     $args[] = 'NULL';
					elseif(is_bool($arg))     $args[] = ($arg) ? 'true' : 'false';
					elseif(is_object($arg))   $args[] = get_class($arg);
					elseif(is_resource($arg)) $args[] = get_resource_type($arg);
					else                      $args[] = $arg;
				}

				$args = implode(', ', $args);
			}

			$error .= sprintf("#%s %s(%s): %s(%s)\r\n", $count, $frame['file'], $frame['line'], $frame['function'], $args);
			$count++;
		}

		return $error;
	}
}
