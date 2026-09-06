<?php
// includes/functions.php
// Contains reusable helper functions

session_start(); // Start session on all pages that include this file

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
