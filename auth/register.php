<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            // Hash password with bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
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
        <a class="nav-link active" href="login.php">Login <i class="bi bi-person-circle ms-1"></i></a>
      </div>
    </div>
  </nav>

  <div class="auth-card">
    <h2 class="fw-bold text-center mb-1">Create an Account</h2>
    <p class="text-muted text-center mb-4">Join EduNest today</p>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST" novalidate>
      <div class="mb-3">
        <label for="registerUsername" class="form-label">Username</label>
        <input type="text" class="form-control" id="registerUsername" name="username" placeholder="Choose a username" required>
      </div>

      <div class="mb-3">
        <label for="registerEmail" class="form-label">Email address</label>
        <input type="email" class="form-control" id="registerEmail" name="email" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label for="registerPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Create a password" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Sign Up</button>

      <p class="text-center small mb-0">Already have an account? <a href="login.php">Login</a></p>
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
