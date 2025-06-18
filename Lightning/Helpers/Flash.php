<?php
/**
 * Flash class for Lightning 2
 *
 * PHP version 8.2
 * 
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Helpers;

class Flash {
	public const SUCCESS = 'success';
	public const INFO    = 'info';
	public const WARNING = 'warning';
	public const ERROR   = 'error';

	/**
	 * Add a message
	 *
	 * @param string $message - the message to show
	 * @param string $type - the type of message (success, warning, info)
	 *
	 * @return void
	 */
	public static function addMessage(string $message, string $type = 'success'): void {
		// create a notifications array in the session if it doesn't exist
		if(!isset($_SESSION['flash_notifications'])) {
			$_SESSION['flash_notifications'] = [];
		}

		// add the message to the notifications array
		$_SESSION['flash_notifications'][] = [
            'body' => $message,
			'type' => $type
		];
	}

	/**
	 * Get all flash messages
	 *
	 * @return array of messages if there are any
	 */
	public static function getMessages(): array {
		$messages = $_SESSION['flash_notifications'] ?? [];

		if(isset($_SESSION['flash_notifications'])) {
			unset($_SESSION['flash_notifications']);
		}

		return $messages;
	}
}
