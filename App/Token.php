<?php

namespace App;

/**
 * Token class
 *
 * PHP version 7.0
 */
class Token {
	protected $token;

	/**
	 * Constructor creates a new random token
	 *
	 * @param string $token - optional existing token
	 */
	public function __construct($token = null) {
		if($token) {
			$this->token = $token;
		} else {
			// 16 bytes = 128 bits = 32 hex characters
			$this->token = bin2hex(random_bytes(16));
		}
	}

	/**
	 * Get the token value
	 *
	 * @return string - the token value
	 */
	public function getValue() {
		return $this->token;
	}

	/**
	 * Get the hashed token value
	 *
	 * @return string - the hashed token value
	 */
	public function getHash() {
		// sha256 = 64 characters
		return hash_hmac('sha256', $this->token, Config::ENCRYPTION_KEY);
	}
}
