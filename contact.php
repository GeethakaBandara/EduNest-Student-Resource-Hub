<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['fullName']);
    $email = sanitize($_POST['email']);
    $message = sanitize($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $message])) {
            setFlashMessage('success', 'Thank you! Your message has been sent successfully.');
        } else {
            setFlashMessage('danger', 'Something went wrong. Please try again.');
        }
    } else {
        setFlashMessage('danger', 'Please fill in all required fields.');
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
        </ul>
        <?php if (isLoggedIn()): ?>
          <a class="nav-link" href="dashboard.php"><?= htmlspecialchars($_SESSION['username']); ?></a>
        <?php else: ?>
          <a class="nav-link" href="auth/login.php">Login <i class="bi bi-person-circle ms-1"></i></a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <h1 class="fw-bold mb-2">Contact</h1>
    <hr class="mb-4">

    <?php displayFlashMessage(); ?>

    <div class="contact-card">
      <h4 class="fw-bold mb-4">Send us a message</h4>

      <form action="contact.php" method="POST">
        <div class="mb-3">
          <label for="fullName" class="form-label">Full name</label>
          <input type="text" class="form-control" name="fullName" id="fullName" placeholder="Enter your full name" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="name@tec.rjt.ac.lk" required>
        </div>

        <div class="mb-4">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" name="message" id="message" rows="4" placeholder="Tell us how we can help..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>