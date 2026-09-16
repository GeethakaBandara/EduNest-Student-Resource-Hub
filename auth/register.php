<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        if ($password !== $confirm_password) {
            $error = 'මුරපද දෙක ගැලපෙන්නේ නැත!';
        } else {
            // Check if user already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'මෙම Email එක හෝ Username එක දැනටමත් භාවිතයේ ඇත.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                
                if ($stmt->execute([$username, $email, $hashedPassword])) {
                    header("Location: login.php?registered=success");
                    exit();
                } else {
                    $error = 'ලියාපදිංචි වීම අසාර්ථක විය. නැවත උත්සාහ කරන්න.';
                }
            }
        }
    } else {
        $error = 'කරුණාකර සියලු විස්තර ඇතුළත් කරන්න.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>

  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="../index.php">Edunext</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="../browse.php">Browse</a></li>
          <li class="nav-item"><a class="nav-link" href="../contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="../upload.php">Upload</a></li>
        </ul>
        <a class="nav-link" href="login.php">Login <i class="bi bi-person-circle ms-1"></i></a>
      </div>
    </div>
  </nav>

  <div class="auth-card my-5 mx-auto" style="max-width: 450px; padding: 20px;">
    <h2 class="fw-bold text-center mb-1">Create Account</h2>
    <p class="text-muted text-center mb-4">Sign up to get started</p>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required>
      </div>

      <div class="mb-3">
        <label for="regEmail" class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" id="regEmail" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label for="regPassword" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="regPassword" placeholder="Enter password" required>
      </div>

      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" id="confirmPassword" placeholder="Confirm password" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Sign Up</button>
      <p class="text-center small mb-0">Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>