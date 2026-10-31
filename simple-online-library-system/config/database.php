<?php
// /config/database.php

// --- ADD THIS LINE ---
// Define the base path for your project.
// If your project is at the root of your domain (e.g., http://mylibrary.com), this should be '/'.
// If it's in a subfolder (e.g., http://localhost/library-system), this should be '/library-system/'.
define('BASE_PATH', '/simple-online-library-system/');
// --- END OF ADDED LINE ---


// Read connection settings from environment variables (Docker) with local defaults.
define('DB_SERVER', getenv('DB_SERVER') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'library_system_db');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>