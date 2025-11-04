<?php
// Database configuration
// Copy this file to config.php and update with your database credentials

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lms');

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

