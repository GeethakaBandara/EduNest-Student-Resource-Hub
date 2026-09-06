<?php
// includes/functions.php
// Contains reusable helper functions

// Only start session if it hasn't been started yet (prevents errors)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize user input to prevent XSS (Cross-Site Scripting)
 * @param string $data The input data
 * @return string Sanitized data
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Check if a user is logged in
 * @return bool True if logged in, false otherwise
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Redirect user to a specific page
 * @param string $url The URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
