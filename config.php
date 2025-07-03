<?php
// Database configuration
$host = 'localhost';
$db   = 'tinta_store';    // Make sure this matches your actual database name
$user = 'root';           // Default XAMPP MySQL user
$pass = '';               // Default password is empty
$charset = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // If connection fails, display error and stop execution
    die("Database connection failed: " . $e->getMessage());
}
