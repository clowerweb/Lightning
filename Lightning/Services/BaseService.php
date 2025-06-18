<?php
/**
 * Base Service for Lightning 2
 * 
 * All services extend this base class
 *
 * PHP version 8.2
 *
 * @since 2.0.0
 * @package Lightning
 */
declare(strict_types = 1);

namespace Lightning\Services;

use PDO;

abstract class BaseService {
    protected PDO $db;

    /**
     * Constructor
     */
    public function __construct() {
        // Get database connection
        $this->db = new PDO(
            "mysql:host=" . $_ENV['DATABASE_HOST'] . ";dbname=" . $_ENV['DATABASE_NAME'],
            $_ENV['DATABASE_USER'],
            $_ENV['DATABASE_PASS']
        );

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
