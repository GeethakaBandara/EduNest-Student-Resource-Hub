<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Fetch user from database
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            redirect('../dashboard.php');
        } else {
            // Login failed
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Login</title>

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
        <a class="nav-link active" href="login.php">Login <i class="bi bi-person-circle ms-1"></i></a>
      </div>
    </div>
  </nav>

  <div class="auth-card">
    <h2 class="fw-bold text-center mb-1">Welcome back!</h2>
    <p class="text-muted text-center mb-4">Login to your account</p>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Note: action="" posts to the same page, method="POST" added, and name attributes added -->
    <form id="loginForm" action="login.php" method="POST" novalidate>
      <div class="mb-3">
        <label for="loginEmail" class="form-label">Email address</label>
        <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Enter your email" required>
        <div class="invalid-feedback">Please enter a valid email address.</div>
      </div>

      <div class="mb-3">
        <label for="loginPassword" class="form-label">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter your password" required>
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">&#128065;</button>
        </div>
        <div class="invalid-feedback d-block" style="display:none !important;"></div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="rememberMe">
          <label class="form-check-label small" for="rememberMe">Remember me</label>
        </div>
        <a href="#" class="small">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>

      <p class="text-center small mb-0">Don't have an account? <a href="register.php">Sign up</a></p>
    </form>
  </div>

  <footer class="d-flex justify-content-between align-items-center flex-wrap">
    <small>&copy; 2024 Edunext Inc. All rights reserved.</small>
    <div>
      <a href="#" class="text-muted me-3" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
      <a href="#" class="text-muted me-3" aria-label="GitHub"><i class="bi bi-github"></i></a>
      <a href="#" class="text-muted me-3" aria-label="Discord"><i class="bi bi-discord"></i></a>
      <a href="#" class="text-muted" aria-label="Email"><i class="bi bi-envelope"></i></a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/script.js"></script>
</body>
</html>
