<?php
/**
 * Helper Functions
 * 
 * This file contains utility functions that can be used throughout the application.
 */

/**
 * Format a date to a human-readable format
 * 
 * @param string $date The date to format
 * @param string $format The format to use (default: 'd M Y')
 * @return string The formatted date
 */
function formatDate($date, $format = 'd M Y')
{
    if (empty($date))
        return '-';
    return date($format, strtotime($date));
}

/**
 * Format a number as currency (Indonesian Rupiah)
 * 
 * @param float $amount The amount to format
 * @param bool $withSymbol Whether to include the currency symbol (default: true)
 * @return string The formatted currency
 */
function formatCurrency($amount, $withSymbol = true)
{
    if (!is_numeric($amount))
        return '-';

    $formatted = number_format($amount, 0, ',', '.');
    return $withSymbol ? 'Rp ' . $formatted : $formatted;
}

/**
 * Sanitize input data to prevent XSS attacks
 * 
 * @param string $data The data to sanitize
 * @return string The sanitized data
 */
function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Generate a random string
 * 
 * @param int $length The length of the string to generate (default: 10)
 * @return string The generated string
 */
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Check if a string starts with a specific substring
 * 
 * @param string $haystack The string to search in
 * @param string $needle The substring to search for
 * @return bool True if the string starts with the substring, false otherwise
 */
function startsWith($haystack, $needle)
{
    return substr($haystack, 0, strlen($needle)) === $needle;
}

/**
 * Check if a string ends with a specific substring
 * 
 * @param string $haystack The string to search in
 * @param string $needle The substring to search for
 * @return bool True if the string ends with the substring, false otherwise
 */
function endsWith($haystack, $needle)
{
    return substr($haystack, -strlen($needle)) === $needle;
}

/**
 * Truncate a string to a specific length and append ellipsis if needed
 * 
 * @param string $string The string to truncate
 * @param int $length The maximum length of the string (default: 100)
 * @param string $append The string to append if truncated (default: '...')
 * @return string The truncated string
 */
function truncateString($string, $length = 100, $append = '...')
{
    if (strlen($string) <= $length) {
        return $string;
    }

    $string = substr($string, 0, $length);
    return rtrim($string) . $append;
}

/**
 * Get the current URL
 * 
 * @return string The current URL
 */
function getCurrentUrl()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return $protocol . '://' . $host . $uri;
}

/**
 * Redirect to a specific URL
 * 
 * @param string $url The URL to redirect to
 * @return void
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * Get a value from the $_GET superglobal with sanitization
 * 
 * @param string $key The key to get
 * @param mixed $default The default value if the key doesn't exist
 * @return mixed The value
 */
function getParam($key, $default = null)
{
    return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
}

/**
 * Get a value from the $_POST superglobal with sanitization
 * 
 * @param string $key The key to get
 * @param mixed $default The default value if the key doesn't exist
 * @return mixed The value
 */
function postParam($key, $default = null)
{
    return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
}

/**
 * Check if the current request is an AJAX request
 * 
 * @return bool True if the request is an AJAX request, false otherwise
 */
function isAjaxRequest()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Get the client's IP address
 * 
 * @return string The IP address
 */
function getClientIp()
{
    $ipAddress = '';

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipAddress = 'UNKNOWN';
    }

    return $ipAddress;
}

/**
 * Debug function to print variables in a readable format
 * 
 * @param mixed $var The variable to debug
 * @param bool $die Whether to die after printing (default: true)
 * @return void
 */
function debug($var, $die = true)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';

    if ($die) {
        die();
    }
}

/**
 * Set a flash message to be displayed on the next page
 * 
 * @param string $message The message to display
 * @param string $type The type of message (success, danger, warning, info)
 * @return void
 */
function setFlashMessage($message, $type = 'info')
{
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Get the flash message from session
 * 
 * @return array|null The flash message or null if none exists
 */
function getFlashMessage()
{
    return $_SESSION['flash_message'] ?? null;
}

/**
 * Clear the flash message from session
 * 
 * @return void
 */
function clearFlashMessage(): void
{
    if (isset($_SESSION['flash_message'])) {
        unset($_SESSION['flash_message']);
    }
}

/**
 * Generate a CSRF token and store it in the session
 * 
 * @return string The generated CSRF token
 */
function generateCsrfToken()
{
    $token = bin2hex(random_bytes(32));
    $_SESSION[CSRF_TOKEN_NAME] = $token;
    return $token;
}

/**
 * Validate a CSRF token
 * 
 * @param string $token The token to validate
 * @return bool Whether the token is valid
 */
function validateCsrfToken($token)
{
    if (!isset($_SESSION[CSRF_TOKEN_NAME]) || $_SESSION[CSRF_TOKEN_NAME] !== $token) {
        return false;
    }
    return true;
}

/**
 * Check if the current user is authenticated
 * 
 * @return bool Whether the user is authenticated
 */
function isAuthenticated()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if the current user has a specific role
 * 
 * @param string|array $roles The role(s) to check for
 * @return bool True if the user has the role, false otherwise
 */
function hasRole($roles)
{
    if (!isAuthenticated()) {
        return false;
    }

    if (!isset($_SESSION['user_role'])) {
        return false;
    }

    if (is_array($roles)) {
        return in_array($_SESSION['user_role'], $roles);
    }

    return $_SESSION['user_role'] === $roles;
}

/**
 * Get the current user's ID
 * 
 * @return int|null The user ID or null if not authenticated
 */
function getCurrentUserId()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get the current user's name
 * 
 * @return string|null The user name or null if not authenticated
 */
function getCurrentUserName()
{
    return $_SESSION['user_name'] ?? null;
}

/**
 * Get the current user's role
 * 
 * @return string|null The user role or null if not authenticated
 */
function getCurrentUserRole()
{
    return $_SESSION['user_role'] ?? null;
}

/**
 * Check if the current page matches a specific URL pattern
 * 
 * @param string $pattern The URL pattern to check
 * @return bool Whether the current page matches the pattern
 */
function isCurrentPage($pattern)
{
    return strpos($_SERVER['REQUEST_URI'], $pattern) !== false;
}
