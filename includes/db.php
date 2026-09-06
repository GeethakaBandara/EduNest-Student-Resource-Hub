<?php
// includes/db.php
// This file handles the database connection using PDO

$host = 'localhost';
$dbname = 'student_resource_hub';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception to catch errors easily
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // If connection fails, display error and stop execution
    die("Database connection failed: " . $e->getMessage());
}
?>
