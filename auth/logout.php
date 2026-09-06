<?php
require_once '../includes/functions.php';

// Destroy the session
session_unset();
session_destroy();

// Redirect to index page
redirect('../index.php');
?>
