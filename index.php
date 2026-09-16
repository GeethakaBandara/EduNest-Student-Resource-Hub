<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';


$stmt = $pdo->query("SELECT * FROM resources ORDER BY created_at DESC LIMIT 3");
$recentResources = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Student Resource Hub</title>
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
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="browse.php">Browse</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
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

  <section class="hero-section text-center py-5 bg-light mb-5">
    <div class="container">
      <h1 class="display-5 fw-bold mb-3">Find the study material you need</h1>
      <p class="lead text-muted">Shared by your fellow students</p>
      <a href="browse.php" class="btn btn-primary btn-lg mt-3">Browse Resources</a>
    </div>
  </section>

  <section class="container" id="recent-resources">
    <h3 class="fw-bold mb-4">Recently Added Resources</h3>
    <div class="row g-4 mb-5">
      <?php foreach ($recentResources as $resource): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($resource['category']); ?></span>
            <h5 class="fw-bold"><?= htmlspecialchars($resource['title']); ?></h5>
            <a href="browse.php" class="btn btn-sm btn-link p-0">View Details</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>