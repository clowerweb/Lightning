<?php
/**
 * Test Service for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace App\Services;

use Lightning\Services\BaseService;

class TestService extends BaseService {
    /**
     * Test method
     *
     * @return string
     */
    public static function test(): string {
        return 'Hello from the Test Service!';
    }
}
