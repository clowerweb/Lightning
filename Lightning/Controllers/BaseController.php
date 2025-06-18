<?php
/**
 * Base Controller for Lightning 2
 * 
 * All controllers extend this base class
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Controllers;

abstract class BaseController {
    /**
     * Constructor
     */
    public function __construct() {
        // Common initialization for all controllers
    }

    /**
     * Method to be executed before controller actions
     * Can be overridden by child controllers to perform pre-action tasks
     * such as authentication, authorization, etc.
     * 
     * @return void
     */
    public function before(): void {
        // Default implementation does nothing
        // Child classes can override this method
    }

    /**
     * Get request data from JSON body
     * 
     * @return array Decoded JSON data
     */
    protected function getRequestData(): array {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        
        return $data;
    }
}
