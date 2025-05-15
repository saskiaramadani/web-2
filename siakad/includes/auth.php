<?php
/**
 * Authentication Functions
 * 
 * This file contains functions for user authentication and authorization.
 */

/**
 * Authenticate a user
 * 
 * @param string $username The username
 * @param string $password The password
 * @return bool Whether the authentication was successful
 */
function authenticateUser($username, $password) {
    $userModel = new User();
    $user = $userModel->getByUsername($username);
    
    if (!$user) {
        return false;
    }
    
    if (!$userModel->verifyPassword($password, $user['password'])) {
        return false;
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_username'] = $user['username'];
    $_SESSION['last_activity'] = time();
    
    return true;
}

/**
 * Log out the current user
 * 
 * @return void
 */
function logoutUser() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
}

/**
 * Check if the user is logged in
 * 
 * @return bool Whether the user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Require user to be logged in
 * 
 * @param string $redirect The URL to redirect to if not logged in
 * @return void
 */
function requireLogin($redirect = '/login.php') {
    if (!isLoggedIn()) {
        setFlashMessage('Please log in to access this page.', 'warning');
        redirect(APP_URL . $redirect);
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        logoutUser();
        setFlashMessage('Your session has expired. Please log in again.', 'warning');
        redirect(APP_URL . $redirect);
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

/**
 * Require user to have a specific role
 * 
 * @param string|array $roles The required role(s)
 * @param string $redirect The URL to redirect to if not authorized
 * @return void
 */
function requireRole($roles, $redirect = '/dashboard.php') {
    requireLogin();
    
    if (!hasRole($roles)) {
        setFlashMessage('You do not have permission to access this page.', 'danger');
        redirect(APP_URL . $redirect);
    }
}

/**
 * Get the current user's data
 * 
 * @return array|null The user data or null if not logged in
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $userModel = new User();
    return $userModel->getById($_SESSION['user_id']);
}
