<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}


function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: auth/login.php");
        exit();
    }
}


function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, 
        'message' => $message
    ];
}


function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_message']['type'];
        $msg = $_SESSION['flash_message']['message'];
        
        echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$msg}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
        
        unset($_SESSION['flash_message']);
    }
}


function isValidFileType($fileName) {
    $allowedExtensions = ['pdf', 'docx', 'mp4'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    return in_array($fileExtension, $allowedExtensions);
}

function isValidFileSize($fileSize) {
    $maxSize = 50 * 1024 * 1024; 
    return $fileSize <= $maxSize;
}
?>