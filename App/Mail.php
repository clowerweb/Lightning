<?php

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
	public static function send($to, $subject, $text, $html) {
		$mail = new \PHPMailer;

		if(Config::MAIL_SMTP) {
			$mail->isSMTP();
			$mail->Host       = Config::MAIL_HOST;
			$mail->SMTPAuth   = Config::MAIL_AUTH;
			$mail->Username   = Config::MAIL_USER;
			$mail->Password   = Config::MAIL_PASS;
			$mail->SMTPSecure = Config::MAIL_SECU;
			$mail->Port       = Config::MAIL_PORT;
		}

		$mail->setFrom(Config::MAIL_FROM);
		$mail->addAddress($to);
		$mail->addReplyTo(Config::MAIL_FROM);
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $html;
		$mail->AltBody = $text;

		return $mail->send();
	}
}
