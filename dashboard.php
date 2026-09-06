<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Require login
if (!is_logged_in()) {
    redirect('auth/login.php');
}

// Fetch resources for the logged-in user
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM resources WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $user_id]);
    $resources = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error loading your resources: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Dashboard</title>

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
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="upload.php">Upload</a></li>
          <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        </ul>
        <span class="navbar-text me-3 d-none d-lg-block text-muted">
          Welcome, <?= htmlspecialchars($_SESSION['username']) ?>
        </span>
        <a class="nav-link" href="auth/logout.php">Logout <i class="bi bi-box-arrow-right ms-1"></i></a>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">My Uploads</h2>
      <a href="upload.php" class="btn btn-primary"><i class="bi bi-cloud-arrow-up me-2"></i>Upload New</a>
    </div>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="bg-white p-4 rounded shadow-sm">
      <?php if (!empty($resources) && count($resources) > 0): ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Date Uploaded</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($resources as $res): ?>
              <tr>
                <td><?= htmlspecialchars($res['title']) ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($res['category']) ?></span></td>
                <td><?= date('M d, Y', strtotime($res['created_at'])) ?></td>
                <td>
                  <a href="<?= htmlspecialchars($res['filepath']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">View File</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="bi bi-folder2-open display-1 text-muted mb-3"></i>
          <h4 class="text-muted">You haven't uploaded any resources yet.</h4>
          <p class="text-muted">Share your notes to help other students!</p>
          <a href="upload.php" class="btn btn-outline-primary mt-2">Start Uploading</a>
        </div>
      <?php endif; ?>
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
</body>
</html>
