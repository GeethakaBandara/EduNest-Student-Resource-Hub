<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$query = "SELECT resources.*, users.username FROM resources JOIN users ON resources.user_id = users.id ORDER BY resources.created_at DESC";
$stmt = $pdo->query($query);
$resources = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Browse Resources</title>
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
          <li class="nav-item"><a class="nav-link active" href="browse.php">Browse</a></li>
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

  <div class="container my-4">
    <?php displayFlashMessage(); ?>
    <h1 class="fw-bold mb-4">Browse resources</h1>

    <div class="row g-4">
      <div class="col-lg-9 offset-lg-1">
        <div class="row g-4">
          <?php if (count($resources) > 0): ?>
            <?php foreach ($resources as $item): ?>
              <div class="col-md-6 col-resource">
                <div class="resource-card p-3 bg-white rounded shadow-sm">
                  <span class="badge bg-primary mb-2"><?= htmlspecialchars($item['category']); ?></span>
                  <h5 class="fw-bold mt-1"><?= htmlspecialchars($item['title']); ?></h5>
                  <p class="small text-muted mb-2"><?= htmlspecialchars($item['description']); ?></p>
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">By <?= htmlspecialchars($item['username']); ?></small>
                    <a href="<?= htmlspecialchars($item['file_path']); ?>" class="btn btn-sm btn-outline-primary" download>Download</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No resources uploaded yet.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>