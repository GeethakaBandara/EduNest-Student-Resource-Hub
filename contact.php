<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['fullName']);
    $email = sanitize_input($_POST['email']);
    $message = sanitize_input($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'message' => $message
            ]);
            $success = true;
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Contact</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>


  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Edunext</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="browse.php">Browse</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="upload.php">Upload</a></li>
          <?php if (is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <?php endif; ?>
        </ul>
        <?php if (is_logged_in()): ?>
          <a class="nav-link" href="auth/logout.php">Logout <i class="bi bi-box-arrow-right ms-1"></i></a>
        <?php else: ?>
          <a class="nav-link" href="auth/login.php">Login <i class="bi bi-person-circle ms-1"></i></a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1 class="fw-bold mb-2">Contact</h1>
    <hr class="mb-4">

    <?php if ($success): ?>
    <div class="alert alert-success" id="contactSuccessAlert">
      &#10003; Thank you! Your message has been sent successfully.
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div class="contact-card">
      <h4 class="fw-bold mb-4">Send us a message</h4>

      <form id="contactForm" action="contact.php" method="POST" novalidate>
        <div class="mb-3">
          <label for="fullName" class="form-label">Full name</label>
          <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" required>
          <div class="invalid-feedback">Please enter your full name.</div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="name@tec.rjt.ac.lk" required>
          <div class="invalid-feedback">Please enter a valid email address.</div>
        </div>

        <div class="mb-4">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tell us how we can help..." required></textarea>
          <div class="invalid-feedback">Please enter your message.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit</button>
      </form>
    </div>
  </div>

  <footer class="d-flex justify-content-between align-items-center flex-wrap mt-5">
    <small>&copy; 2024 Edunext Inc. All rights reserved.</small>
    <div>
      <a href="#" class="text-muted me-3" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
      <a href="#" class="text-muted me-3" aria-label="GitHub"><i class="bi bi-github"></i></a>
      <a href="#" class="text-muted me-3" aria-label="Discord"><i class="bi bi-discord"></i></a>
      <a href="#" class="text-muted" aria-label="Email"><i class="bi bi-envelope"></i></a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Removing js/script.js as it might interfere with the normal form submission if it has preventDefault -->
</body>
</html>
