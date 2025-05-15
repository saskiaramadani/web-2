<?php
require_once 'config/app.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Set flash message
session_start();
setFlashMessage('Anda telah berhasil logout.', 'success');

// Redirect to login page
header('Location: login.php');
exit;
