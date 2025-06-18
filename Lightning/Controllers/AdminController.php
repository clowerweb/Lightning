<?php
/**
 * Admin Controller for Lightning
 *
 * PHP version 8.2
 *
 * @since 2.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Controllers;

class AdminController extends BaseController {
    /**
     * Index method
     *
     * @return void
     */
    public function index(): void {
        $indexPath = BASE_PATH . '/public/admin/index.html';
        if (file_exists($indexPath)) {
            readfile($indexPath);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Admin build not found.']);
        }
    }
}