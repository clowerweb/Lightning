<?php

declare(strict_types = 1);

namespace App;

/**
 * Mail class
 *
 * PHP version 7.0
 */
class Mail {
	/**
	 * Send an email message
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $text
	 * @param string $html
	 *
	 * @return boolean
	 */
	public static function send(string $to, string $subject, string $text, ?string $html = null) : bool {
		// Unique boundary
		$boundary = md5(uniqid() . microtime());
		// Add From: header
		$headers  = "From: " . Config::MAIL_FROM_NAME . " <" . Config::MAIL_FROM_EMAIL . ">\r\n";
		// Reply to address
		$headers .= "Reply-to: " . Config::MAIL_REPLY_TO . "\r\n";
		// Specify MIME version 1.0
		$headers .= "MIME-Version: 1.0\r\n";
		// Tell e-mail client this e-mail contains alternate versions
		$headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n\r\n";
		// Plain text version of message
		$body  = "--$boundary\r\n" . "Content-Type: text/plain; charset=ISO-8859-1\r\n" . "Content-Transfer-Encoding: base64\r\n\r\n";
		$body .= chunk_split(base64_encode(strip_tags($text)));
		// HTML version of message
		$body .= "--$boundary\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n" . "Content-Transfer-Encoding: base64\r\n\r\n";
		$body .= chunk_split(base64_encode($html));
		$body .= "--$boundary--";

		// Send Email
		if(is_array($to)) {
			foreach ($to as $e) {
				return mail($e, $subject, $body, $headers);
			}
		} else {
			return mail($to, $subject, $body, $headers);
		}

		return false;
	}
}
