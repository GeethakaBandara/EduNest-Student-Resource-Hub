<?php
// test.php - Temporary diagnostic page
// IMPORTANT: Delete this file after testing!

require_once 'includes/db.php';
require_once 'includes/functions.php';

echo "<h2>EduNest - System Diagnostic</h2>";

// Test 1: Database connection
echo "<h3>1. Database Connection</h3>";
try {
    $pdo->query("SELECT 1");
    echo "<p style='color:green'>✅ Database connected successfully!</p>";
} catch(Exception $e) {
    echo "<p style='color:red'>❌ Database connection FAILED: " . $e->getMessage() . "</p>";
}

// Test 2: Check users table and list users
echo "<h3>2. Registered Users</h3>";
try {
    $stmt = $pdo->query("SELECT id, username, email, created_at FROM users");
    $users = $stmt->fetchAll();
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>";
        foreach ($users as $u) {
            echo "<tr><td>{$u['id']}</td><td>{$u['username']}</td><td>{$u['email']}</td><td>{$u['created_at']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:orange'>⚠️ No users found in the database! Registration may not be saving.</p>";
    }
} catch(Exception $e) {
    echo "<p style='color:red'>❌ Error reading users table: " . $e->getMessage() . "</p>";
}

// Test 3: Session status
echo "<h3>3. Session Status</h3>";
if (is_logged_in()) {
    echo "<p style='color:green'>✅ You are logged in as: " . htmlspecialchars($_SESSION['username']) . " (ID: {$_SESSION['user_id']})</p>";
} else {
    echo "<p style='color:orange'>⚠️ Not logged in (session has no user_id)</p>";
}

// Test 4: Server info
echo "<h3>4. Server Info</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script path: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";

echo "<hr><p><a href='auth/login.php'>Go to Login</a> | <a href='auth/register.php'>Go to Register</a></p>";
echo "<p style='color:red'><strong>Remember to delete test.php after troubleshooting!</strong></p>";
?>
