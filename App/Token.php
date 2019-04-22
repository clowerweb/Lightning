<?php

declare(strict_types = 1);

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
	 * @throws \Exception if it fails
	 *
	 * @param string $token - optional existing token
	 */
	public function __construct(string $token = null) {
		$this->token = $token ? $token : bin2hex(random_bytes(16));
	}

	/**
	 * Get the token value
	 *
	 * @return string - the token value
	 */
	public function getValue() : string {
		return $this->token;
	}

	/**
	 * Get the hashed token value
	 *
	 * @return string - the hashed token value
	 */
	public function getHash() : string {
		// sha256 = 64 characters
		return hash_hmac('sha256', $this->token, Config::ENCRYPTION_KEY);
	}
}
