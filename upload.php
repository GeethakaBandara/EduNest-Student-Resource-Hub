<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resource_file'])) {
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $userId = $_SESSION['user_id'];

    $file = $_FILES['resource_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        if (isValidFileType($file['name']) && isValidFileSize($file['size'])) {
            $fileName = time() . '_' . basename($file['name']);
            $targetPath = "uploads/" . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $stmt = $pdo->prepare("INSERT INTO resources (user_id, title, category, description, file_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $title, $category, $description, $targetPath]);
                
                setFlashMessage('success', 'Resource uploaded successfully!');
                header("Location: browse.php");
                exit();
            } else {
                setFlashMessage('danger', 'Failed to upload file.');
            }
        } else {
            setFlashMessage('danger', 'Invalid file type or size exceeded limit (50MB).');
        }
    } else {
        setFlashMessage('danger', 'Error uploading file.');
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
        </ul>
        <a class="nav-link" href="dashboard.php"><?= htmlspecialchars($_SESSION['username']); ?></a>
      </div>
    </div>
  </nav>

  <div class="container my-5" style="max-width: 750px;">
    <?php displayFlashMessage(); ?>
    <div class="bg-white rounded shadow-sm p-4 p-md-5">
      <h2 class="fw-bold mb-1">Share Your Knowledge</h2>
      <p class="text-muted mb-4">Complete the details below to publish your content.</p>

      <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="upload-dropzone mb-4" id="uploadDropzone">
          <div class="upload-icon-circle">&#8593;</div>
          <h5 class="fw-bold">Select a file</h5>
          <p class="text-muted small">PDF, DOCX, or MP4 up to 50MB</p>
          <input type="file" name="resource_file" id="fileInput" class="form-control" required>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="resourceTitle" class="form-label fw-bold">Title</label>
            <input type="text" name="title" class="form-control" id="resourceTitle" placeholder="e.g. Advanced Calculus Notes" required>
          </div>
          <div class="col-md-6">
            <label for="resourceCategory" class="form-label fw-bold">Category</label>
            <select name="category" class="form-select" id="resourceCategory" required>
              <option value="Notes">Notes</option>
              <option value="Assignment">Assignment</option>
              <option value="Pass papers">Pass papers</option>
              <option value="Practice problem">Practice problem</option>
            </select>
          </div>
        </div>

        <div class="mb-4">
          <label for="resourceDescription" class="form-label fw-bold">Description</label>
          <textarea name="description" class="form-control" id="resourceDescription" rows="4" placeholder="Briefly describe what this resource covers..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Start Upload &rarr;</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>