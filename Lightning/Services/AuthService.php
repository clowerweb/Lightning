<?php
/**
 * Auth Service for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 2.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Services;

use Lightning\Helpers\Token;
use PDO;

class AuthService extends BaseService {
    /**
     * User login
     * 
     * @return array Token or error
     */
    public function login(string $username, string $password): array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username AND admin = 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Generate a token
            $token = Token::generate(['uid' => $user['id']]);
            return ['token' => $token];
        }

        return ['error' => 'Invalid credentials'];
    }
}
