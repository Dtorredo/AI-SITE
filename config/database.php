<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'stepcash_aurai');
define('DB_USER', 'stepcash_aurai');
define('DB_PASS', 'Savvytonny@apeli99');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Create database connection
function getDBConnection() {
    static $conn;
    
    if (!isset($conn)) {
        try {
            $conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            http_response_code(500);
            die(json_encode(['error' => 'Database connection failed. Please try again later.']));
        }
    }
    
    return $conn;
}
?>
