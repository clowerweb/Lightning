<?php
declare(strict_types = 1);

use Lightning\Helpers\Utilities;
use Dotenv\Dotenv;

/**
 * Default time zone to use application-wide. It is HIGHLY RECOMMENDED that you LEAVE THIS AS 'UTC', because it is
 * the universal time, even if you are located in a different time zone. Several utility functions require times to
 * be in UTC for proper usage, and there are utilities to convert UTC times to other time zones for display. You
 * probably should not change this!
 */
date_default_timezone_set('UTC');

/**
 * Composer autoloader
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define the base directory
$basePath = Utilities::getAbsRoot();

// Load environment variables
if (file_exists($basePath . '/.env')) {
    $dotenv = Dotenv::createImmutable($basePath);
    $dotenv->load();
}

// Check if already installed
if ($_ENV['INSTALLED'] === 'true') {
    Utilities::redirect('/');
}

/**
 * Error and exception handler
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_error_handler('Lightning\\Helpers\\Error::errorHandler');
set_exception_handler('Lightning\\Helpers\\Error::exceptionHandler');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get post data
    $environment = $_POST['environment'] ?? 'dev';
    $db_host = $_POST['db_host'] ?? '';
    $db_name = $_POST['db_name'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';
    $admin_user = $_POST['admin_user'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $admin_pass = $_POST['admin_pass'] ?? '';

    // Simple validation
    if (empty($db_host)) $errors[] = 'Database Host is required';
    if (empty($db_name)) $errors[] = 'Database Name is required';
    if (empty($db_user)) $errors[] = 'Database Username is required';
    if (empty($admin_user)) $errors[] = 'Admin Username is required';
    if (empty($admin_email)) $errors[] = 'Admin Email is required';
    if (!empty($admin_email) && !filter_var($admin_email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid Admin Email format';
    if (empty($admin_pass)) $errors[] = 'Admin Password is required';

    if (empty($errors)) {
        // Create .env file
        $env_content = file_get_contents(__DIR__ . '/../.env.example');
        $env_content = preg_replace("/ENVIRONMENT=.+/i", "ENVIRONMENT={$environment}", $env_content);
        $env_content = preg_replace("/DATABASE_HOST=.+/i", "DATABASE_HOST={$db_host}", $env_content);
        $env_content = preg_replace("/DATABASE_NAME=.+/i", "DATABASE_NAME={$db_name}", $env_content);
        $env_content = preg_replace("/DATABASE_USER=.+/i", "DATABASE_USER={$db_user}", $env_content);
        $env_content = preg_replace("/DATABASE_PASS=.+/i", "DATABASE_PASS={$db_pass}", $env_content);
        $env_content = preg_replace("/MYSQL_DATABASE=.+/i", "MYSQL_DATABASE={$db_name}", $env_content);
        $env_content = preg_replace("/MYSQL_USER=.+/i", "MYSQL_USER={$db_user}", $env_content);
        $env_content = preg_replace("/MYSQL_ROOT_PASSWORD=.+/i", "MYSQL_ROOT_PASSWORD={$db_pass}", $env_content);
        $env_content = preg_replace("/INSTALLED=.+/i", "INSTALLED=true", $env_content);
        file_put_contents(__DIR__ . '/../.env', $env_content);

        try {
            // Create database and user
            $pdo = new PDO("mysql:host={$db_host}", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}`");
            $pdo->exec("USE `{$db_name}`");

            // Import schema
            $schema = file_get_contents(__DIR__ . '/../_dev/schema.sql');
            $pdo->exec($schema);

            // Import seeds
            $seeds = file_get_contents(__DIR__ . '/../_dev/seeds.sql');
            $pdo->exec($seeds);

            // Create admin user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (:username, :email, :password, 1)");
            $stmt->execute([
                'username' => $admin_user,
                'email' => $admin_email,
                'password' => password_hash($admin_pass, PASSWORD_DEFAULT),
            ]);

            // Redirect to admin
            header('Location: /admin');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lightning Installer</title>
        <style>
            body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; }
            form { background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            h1 { text-align: center; }
            .form-group { margin-bottom: 1rem; }
            label { display: block; margin-bottom: 0.5rem; }
            input, select { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.25rem; }
            button { width: 100%; padding: 0.75rem; background-color: #007bff; color: white; border: none; border-radius: 0.25rem; cursor: pointer; }
            .errors { color: red; margin-bottom: 1rem; }
        </style>
    </head>
    <body>
        <form method="POST">
            <h1>Lightning Installer</h1>
            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="environment">Environment</label>
                <select name="environment" id="environment">
                    <option value="dev">Development</option>
                    <option value="prod">Production</option>
                </select>
            </div>
            <div class="form-group">
                <label for="db_host">Database Host</label>
                <input type="text" name="db_host" id="db_host" value="db" required>
            </div>
            <div class="form-group">
                <label for="db_name">Database Name</label>
                <input type="text" name="db_name" id="db_name" value="lightning" required>
            </div>
            <div class="form-group">
                <label for="db_user">Database Username</label>
                <input type="text" name="db_user" id="db_user" value="root" required>
            </div>
            <div class="form-group">
                <label for="db_pass">Database Password</label>
                <input type="password" name="db_pass" id="db_pass" value="password">
            </div>
            <hr>
            <div class="form-group">
                <label for="admin_user">Admin Username</label>
                <input type="text" name="admin_user" id="admin_user" value="admin" required>
            </div>
            <div class="form-group">
                <label for="admin_email">Admin Email</label>
                <input type="email" name="admin_email" id="admin_email" required>
            </div>
            <div class="form-group">
                <label for="admin_pass">Admin Password</label>
                <input type="password" name="admin_pass" id="admin_pass" required>
            </div>
            <button type="submit">Install</button>
        </form>
    </body>
</html>
