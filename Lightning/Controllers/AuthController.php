<?php
/**
 * Auth Controller for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 2.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Controllers;

use Lightning\Services\AuthService as Service;

class AuthController extends BaseController {
    private ?Service $service;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->service = new Service();
    }

    /**
     * User login
     * 
     * @return array Token or error
     */
    public function login(string $username, string $password): array {
        return $this->service->login($username, $password);
    }
}
