<?php
/**
 * Test Model for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace App\Models;

use Lightning\Models\BaseModel;

class TestModel extends BaseModel {
    /**
     * Test method
     *
     * @return string
     */
    public static function test(): string {
        return 'Hello from the Test Model! ';
    }
}
