<?php
/**
 * Token Helper for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Random\RandomException;
use Exception;

class Token {
	protected string $token;

    /**
     * Constructor creates a new random token
     *
     * @param string|null $token - optional existing token
     *
     * @throws RandomException
     */
	public function __construct(?string $token = null) {
		$this->token = $token ?: bin2hex(random_bytes(16));
	}

	/**
	 * Get the token value
	 *
	 * @return string - the token value
	 */
	public function getValue(): string {
		return $this->token;
	}

	/**
	 * Get the hashed token value
	 *
	 * @return string - the hashed token value
	 */
	public function getHash(): string {
		// sha256 = 64 characters
		return hash_hmac('sha256', $this->token, $_ENV['ENCRYPTION_KEY']);
	}

	/**
     * Generate a JWT
     *
     * @param array $payload
     * @return string
     */
    public static function generate(array $payload): string {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // jwt valid for 1 hour
        $payload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ]);

        return JWT::encode($payload, $_ENV['ENCRYPTION_KEY'], 'HS256');
    }

    /**
     * Validate a JWT
     *
     * @param string $token
     * @return object|null
     */
    public static function validate(string $token): ?object {
        try {
            return JWT::decode($token, new Key($_ENV['ENCRYPTION_KEY'], 'HS256'));
        } catch (Exception $e) {
            return null;
        }
    }
}
