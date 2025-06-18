<?php
/**
 * Mail Helper for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    /**
     * Send an email message
     *
     * @param string|array $to
     * @param string $subject
     * @param string $text
     * @param string|null $html
     *
     * @return boolean
     */
	public static function send(string|array $to, string $subject, string $text, ?string $html = null): bool {
        if (isset($_ENV['MAIL_DRIVER']) && $_ENV['MAIL_DRIVER'] === 'smtp') {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = $_ENV['MAIL_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['MAIL_USERNAME'];
                $mail->Password   = $_ENV['MAIL_PASSWORD'];
                $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = (int) ($_ENV['MAIL_PORT'] ?? 587);

                //Recipients
                $mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
                if (is_array($to)) {
                    foreach ($to as $email) {
                        $mail->addAddress($email);
                    }
                } else {
                    $mail->addAddress($to);
                }
                $mail->addReplyTo($_ENV['MAIL_REPLY_TO']);

                // Content
                $mail->isHTML($html !== null);
                $mail->Subject = $subject;
                $mail->Body    = $html ?? $text;
                $mail->AltBody = strip_tags($text);

                return $mail->send();
            } catch (Exception $e) {
                // Fallback to mail()
            }
        }

		// Unique boundary
		$boundary = md5(uniqid() . microtime());
		// Add From: header
		$headers  = "From: " . $_ENV['MAIL_FROM_NAME'] . " <" . $_ENV['MAIL_FROM_EMAIL'] . ">\r\n";
		// Reply to address
		$headers .= "Reply-to: " . $_ENV['MAIL_REPLY_TO'] . "\r\n";
		// Specify MIME version 1.0
		$headers .= "MIME-Version: 1.0\r\n";
		// Tell e-mail client this e-mail contains alternate versions
		$headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n\r\n";
		// Plain text version of message
		$body  = "--$boundary\r\n" . "Content-Type: text/plain; charset=UTF-8\r\n" . "Content-Transfer-Encoding: base64\r\n\r\n";
		$body .= chunk_split(base64_encode(strip_tags($text)));
        if ($html) {
            // HTML version of message
            $body .= "--$boundary\r\n" . "Content-Type: text/html; charset=UTF-8\r\n" . "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($html));
        }
		$body .= "--$boundary--";

		// Send Email
		if(is_array($to)) {
            $sent = true;
			foreach ($to as $e) {
				if (!mail($e, $subject, $body, $headers)) {
                    $sent = false;
                }
			}
            return $sent;
		} else {
			return mail($to, $subject, $body, $headers);
		}
	}
}
