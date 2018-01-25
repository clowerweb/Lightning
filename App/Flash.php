<?php

namespace App;

/**
 * Flash class
 *
 * PHP version 7.0
 */
class Flash {
	const SUCCESS = 'success';
	const INFO    = 'info';
	const WARNING = 'warning';

	/**
	 * Add a message
	 *
	 * @param string $message - the message to show
	 * @param string $type - the type of message (success, warning, info)
	 *
	 * @return void
	 */
	public static function addMessage(string $message, string $type = 'success') : void {
		// create a notifications array in the session if it doesn't exist
		if(!isset($_SESSION['flash_notifications'])) {
			$_SESSION['flash_notifications'] = [];
		}

		// add the message to the notifications array
		$_SESSION['flash_notifications'][] = [
			'type' => $type,
			'body' => $message
		];
	}

	/**
	 * Get all flash messages
	 *
	 * @return array of messages if there are any
	 */
	public static function getMessages() : array {
		$messages = $_SESSION['flash_notifications'] ?? [];

		if(isset($_SESSION['flash_notifications'])) {
			unset($_SESSION['flash_notifications']);
		}

		return $messages;
	}
}
