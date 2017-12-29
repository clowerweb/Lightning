<?php

namespace Core;

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

		if(\App\Config::SHOW_ERRORS) {
			echo '<h1>Fatal error</h1>';
			echo '<p>Uncaught exception: "' . get_class($exception) . '"</p>';
			echo '<p>Message: "' . $exception->getMessage() . '"</p>';
			echo '<p>Stack trace: <pre>' . $exception->getTraceAsString() . '</pre></p>';
			echo '<p>Thrown in "' . $exception->getFile() . '" on line ' . $exception->getLine() . '</p>';
		} else {
			$dir = dirname(__DIR__) . '/logs';
			$log = $dir . '/' . date('Y-m-d') . '.txt';

			if(!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}

			ini_set('error_log', $log);

			$message  = 'Uncaught exception: "' . get_class($exception) . '"';
			$message .= ' with message "' . $exception->getMessage() . '"';
			$message .= "\r\nStack trace: " . $exception->getTraceAsString();
			$message .= "\r\nThrown in \""  . $exception->getFile() . "\" on line " . $exception->getLine();

			error_log($message . "\r\n\r\n");
			View::renderTemplate("$code.twig");
		}
	}
}