<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Build the base query
$query = "SELECT * FROM resources WHERE 1=1";
$params = [];

// Handle search keyword
if (!empty($_GET['search'])) {
    $query .= " AND (title LIKE :search OR description LIKE :search)";
    $params['search'] = '%' . $_GET['search'] . '%';
}

// Handle categories (array of checkboxes)
if (!empty($_GET['categories']) && is_array($_GET['categories'])) {
    $categoryPlaceholders = [];
    foreach ($_GET['categories'] as $index => $cat) {
        $key = "cat" . $index;
        $categoryPlaceholders[] = ":" . $key;
        $params[$key] = $cat;
    }
    $query .= " AND category IN (" . implode(',', $categoryPlaceholders) . ")";
}

// Handle study year
if (!empty($_GET['study_year'])) {
    $query .= " AND study_year = :study_year";
    $params['study_year'] = $_GET['study_year'];
}

// Handle semester
if (!empty($_GET['semester'])) {
    $query .= " AND semester = :semester";
    $params['semester'] = $_GET['semester'];
}

$query .= " ORDER BY created_at DESC";

// Execute query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
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
    <h1 class="fw-bold mb-4">Browse resources</h1>

    <form action="browse.php" method="GET">
      <div class="row g-4">
      
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
          <div class="bg-white rounded p-3 shadow-sm mb-3">
            <p class="fw-bold text-uppercase small mb-3">Category</p>

            <?php
            $selected_categories = $_GET['categories'] ?? [];
            $categories_list = ['Notes', 'Assignment', 'Pass papers', 'Practice problem'];
            foreach ($categories_list as $cat):
                $checked = in_array($cat, $selected_categories) ? 'checked' : '';
            ?>
            <div class="form-check mb-2">
              <input class="form-check-input category-checkbox" type="checkbox" name="categories[]" value="<?= htmlspecialchars($cat) ?>" id="cat<?= str_replace(' ', '', $cat) ?>" <?= $checked ?>>
              <label class="form-check-label" for="cat<?= str_replace(' ', '', $cat) ?>"><?= htmlspecialchars($cat) ?></label>
            </div>
            <?php endforeach; ?>

            <p class="fw-bold text-uppercase small mb-2 mt-4">Study year</p>
            <select class="form-select mb-3" name="study_year">
              <option value="">All Years</option>
              <?php
              $selected_year = $_GET['study_year'] ?? '';
              $years = ['Year 1', 'Year 2', 'Year 3', 'Year 4'];
              foreach ($years as $yr):
                  $sel = ($yr == $selected_year) ? 'selected' : '';
                  echo "<option value=\"$yr\" $sel>$yr</option>";
              endforeach;
              ?>
            </select>

            <p class="fw-bold text-uppercase small mb-2">Semester</p>
            <select class="form-select mb-3" name="semester">
              <option value="">All Semesters</option>
              <?php
              $selected_sem = $_GET['semester'] ?? '';
              $sems = ['Semester 1', 'Semester 2'];
              foreach ($sems as $sem):
                  $sel = ($sem == $selected_sem) ? 'selected' : '';
                  echo "<option value=\"$sem\" $sel>$sem</option>";
              endforeach;
              ?>
            </select>

            <button type="submit" class="btn btn-primary w-100" id="applyFiltersBtn">Apply filters</button>
          </div>

          <div class="help-box">
            <h5 class="fw-bold">Need help?</h5>
            <p class="small">Contact our academic advisors for resource guidance.</p>
            <a href="contact.php">Message us</a>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
          <div class="mb-4 d-flex">
            <input type="text" class="form-control me-2" name="search" placeholder="Search by title or keyword..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="btn btn-secondary">Search</button>
          </div>

          <div class="row g-4">
            <?php if (count($resources) > 0): ?>
              <?php foreach ($resources as $res): ?>
              <div class="col-md-6 col-resource">
                <div class="resource-card h-100 d-flex flex-column border rounded shadow-sm overflow-hidden">
                  <div class="p-4 bg-light text-center border-bottom">
                    <i class="bi bi-file-earmark-text display-1 text-primary"></i>
                  </div>
                  <div class="p-3 flex-grow-1">
                    <span class="badge bg-secondary mb-2"><?= htmlspecialchars($res['category']) ?></span>
                    <h5 class="fw-bold mt-1"><?= htmlspecialchars($res['title']) ?></h5>
                    <p class="small text-muted mb-2"><?= htmlspecialchars($res['description']) ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                      <small class="text-muted"><?= date('M d, Y', strtotime($res['created_at'])) ?></small>
                      <a href="<?= htmlspecialchars($res['filepath']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">Download</a>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12 text-center py-5">
                <h4 class="text-muted">No resources found matching your criteria.</h4>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </form>
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
