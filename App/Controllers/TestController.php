<?php
/**
 * Test Controller for Lightning 2
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace App\Controllers;

use Lightning\Controllers\BaseController;
use App\Models\TestModel;
use App\Services\TestService;

class TestController extends BaseController {
    /**
     * Test method
     *
     * @param array $args
     * @return string
     */
    public function test(array $args): string {
        $result  = 'Hello from the Test Controller! ';
        $result .= json_encode($args) . ' ';
        $result .= TestModel::test();
        $result .= TestService::test();

        return $result;
    }
}
