<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Student Resource Hub</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Our own CSS -->
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

  <section class="hero-section">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold mb-4"> Find the study material you need, shared by your fellow students.</h1>

        <form class="d-flex" role="search" action="browse.php" method="GET">
          <input class="form-control me-2" type="search" name="search" placeholder="search resources...">
          <button class="btn btn-primary" type="submit">search</button>
        </form>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <img src="images/meeting.png" alt="Students studying together">
      </div>
    </div>
  </section>

  <section class="container" id="recent-resources">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold">Recently added resources</h3>
      <a href="browse.php" class="text-decoration-none">View all</a>
    </div>

    <!-- For simplicity, displaying some recent resources from the database dynamically -->
    <div class="row g-4 mb-5">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM resources ORDER BY created_at DESC LIMIT 3");
          $recent_resources = $stmt->fetchAll();

          if (count($recent_resources) > 0) {
              foreach ($recent_resources as $resource) {
                  echo '<div class="col-md-4">';
                  echo '  <div class="resource-card">';
                  echo '    <div class="p-3 bg-light text-center border-bottom"><i class="bi bi-file-earmark-text display-4 text-primary"></i></div>'; // Placeholder image logic
                  echo '    <div class="p-3">';
                  echo '      <h6 class="fw-bold mb-0">' . htmlspecialchars($resource['title']) . '</h6>';
                  echo '      <small class="text-muted">' . htmlspecialchars($resource['category']) . '</small>';
                  echo '    </div>';
                  echo '  </div>';
                  echo '</div>';
              }
          } else {
              echo '<div class="col-12"><p class="text-muted">No resources added yet.</p></div>';
          }
      } catch (PDOException $e) {
          echo '<div class="col-12"><p class="text-danger">Error loading resources.</p></div>';
      }
      ?>
    </div>
  </section>

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
  <script src="js/script.js"></script>
</body>
</html>
