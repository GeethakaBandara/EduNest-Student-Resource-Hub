<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Require login
if (!is_logged_in()) {
    redirect('auth/login.php');
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = sanitize_input($_POST['title']);
    $category = sanitize_input($_POST['category']);
    $description = sanitize_input($_POST['description']);
    $study_year = sanitize_input($_POST['study_year']);
    $semester = sanitize_input($_POST['semester']);

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        
        $file_parts = explode('.', $file_name);
        $file_extension = strtolower(end($file_parts));

        $allowed_extensions = array('pdf', 'docx', 'mp4');
        $max_size = 50 * 1024 * 1024; // 50MB

        if (!in_array($file_extension, $allowed_extensions)) {
            $error = "Upload failed. Allowed file types: PDF, DOCX, MP4.";
        } elseif ($file_size > $max_size) {
            $error = "Upload failed. File size exceeds 50MB.";
        } else {
            // Generate a safe unique filename
            $new_file_name = uniqid() . '.' . $file_extension;
            $upload_dir = 'uploads/';
            $dest_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                // Insert into database
                try {
                    $stmt = $pdo->prepare("INSERT INTO resources (user_id, title, description, category, study_year, semester, filename, filepath) VALUES (:user_id, :title, :description, :category, :study_year, :semester, :filename, :filepath)");
                    $stmt->execute([
                        'user_id' => $_SESSION['user_id'],
                        'title' => $title,
                        'description' => $description,
                        'category' => $category,
                        'study_year' => $study_year,
                        'semester' => $semester,
                        'filename' => $file_name,
                        'filepath' => $dest_path
                    ]);
                    $success = "Resource uploaded successfully!";
                } catch(PDOException $e) {
                    $error = "Database error: " . $e->getMessage();
                }
            } else {
                $error = "There was an error moving the uploaded file.";
            }
        }
    } else {
        $error = "Please select a file to upload or check for upload errors.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edunext - Upload</title>

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
          <li class="nav-item"><a class="nav-link active" href="upload.php">Upload</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        </ul>
        <a class="nav-link" href="auth/logout.php">Logout <i class="bi bi-box-arrow-right ms-1"></i></a>
      </div>
    </div>
  </nav>

  <div class="container" style="max-width: 750px;">
    <div class="bg-white rounded shadow-sm p-4 p-md-5">
      <h2 class="fw-bold mb-1">Share Your Knowledge</h2>
      <p class="text-muted mb-4">Complete the details below to publish your content.</p>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>

      <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data">
        
        <div class="mb-4 text-center p-4 border border-2 border-dashed rounded bg-light" id="uploadDropzone">
          <div class="fs-1 text-primary mb-2"><i class="bi bi-cloud-arrow-up"></i></div>
          <h5 class="fw-bold">Select a file</h5>
          <p class="text-muted small">PDF, DOCX, or MP4 up to 50MB</p>

          <input type="file" id="fileInput" name="file" class="form-control mt-3" accept=".pdf,.docx,.mp4" required>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="resourceTitle" class="form-label fw-bold">Title</label>
            <input type="text" class="form-control" id="resourceTitle" name="title" placeholder="e.g. Advanced Calculus Notes" required>
          </div>
          <div class="col-md-6">
            <label for="resourceCategory" class="form-label fw-bold">Category</label>
            <select class="form-select" id="resourceCategory" name="category" required>
              <option value="">Select Category</option>
              <option value="Notes">Notes</option>
              <option value="Assignment">Assignment</option>
              <option value="Pass papers">Pass papers</option>
              <option value="Practice problem">Practice problem</option>
            </select>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="studyYear" class="form-label fw-bold">Study Year</label>
            <select class="form-select" id="studyYear" name="study_year" required>
              <option value="">Select Year</option>
              <option value="Year 1">Year 1</option>
              <option value="Year 2">Year 2</option>
              <option value="Year 3">Year 3</option>
              <option value="Year 4">Year 4</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="semester" class="form-label fw-bold">Semester</label>
            <select class="form-select" id="semester" name="semester" required>
              <option value="">Select Semester</option>
              <option value="Semester 1">Semester 1</option>
              <option value="Semester 2">Semester 2</option>
            </select>
          </div>
        </div>

        <div class="mb-4">
          <label for="resourceDescription" class="form-label fw-bold">Description</label>
          <textarea class="form-control" id="resourceDescription" name="description" rows="4" placeholder="Briefly describe what this resource covers..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Start Upload &rarr;</button>
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
</body>
</html>
