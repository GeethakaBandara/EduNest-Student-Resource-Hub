<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: ../dashboard.php");
            exit();
        } else {
            $error = "in correct";
        }
    } else {
        $error = "try again";
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
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form id="loginForm" action="login.php" method="POST">
      <div class="mb-3">
        <label for="loginEmail" class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" id="loginEmail" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label for="loginPassword" class="form-label">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" name="password" id="loginPassword" placeholder="Enter your password" required>
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">&#128065;</button>
        </div>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/script.js"></script>
</body>
</html>