<?php

/**
 * Application Configuration
 * 
 * This file contains the main configuration settings for the application.
 */

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database configuration
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);

// Application configuration
define('APP_NAME', 'SIAKAD');
define('APP_URL', $_ENV['APP_URL']);

// Application settings
define('DEBUG_MODE', true);
define('SESSION_LIFETIME', 7200); // 2 hours
define('DEFAULT_TIMEZONE', 'Asia/Jakarta');

// Security settings
define('HASH_COST', 10); // For password hashing
define('CSRF_TOKEN_NAME', 'csrf_token');

// File upload settings
define('UPLOAD_DIR', 'uploads');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Set default timezone
date_default_timezone_set(DEFAULT_TIMEZONE);

// Error reporting based on environment
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1') {
    // Development environment
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('ENVIRONMENT', 'development');
} else {
    // Production environment
    error_reporting(0);
    ini_set('display_errors', 0);
    define('ENVIRONMENT', 'production');
}

// Autoload models
spl_autoload_register(function ($class_name) {
    $model_path = __DIR__ . '/../models/' . $class_name . '.php';
    if (file_exists($model_path)) {
        require_once $model_path;
    }
});

// Include helper functions
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/functions.php';