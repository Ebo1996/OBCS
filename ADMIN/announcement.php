<?php
include "../setup/dbconnection.php";


// Initialize variables
$title = $message = '';
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['announcementsubmit'])) {
  // Sanitize and validate input
  $title = htmlspecialchars(trim($_POST['title']));
  $message = htmlspecialchars(trim($_POST['message']));

  // Validate inputs
  if (empty($title) || empty($message)) {
    $error_message = 'Title and message are required!';
  } else {
    try {
      // Connect to database
      $sql = "INSERT INTO announcements (title, body ,posted_at) VALUES (?, ?, NOW())";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $title, $message);


      // Execute query
      if ($stmt->execute()) {
        $success_message = 'Announcement posted successfully!';

        // Clear form fields
        $title = $priority = $message = '';
      } else {
        $error_message = 'Failed to post announcement. Please try again.';
      }
    } catch (PDOException $e) {
      $error_message = 'Database error: ' . $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Announcement Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Animate.css for animations -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
    }

    body {
      background-color: var(--light-bg);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .main-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .form-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .form-header {
      background: linear-gradient(135deg, var(--primary-color), #1a252f);
      color: white;
      padding: 20px;
    }

    .form-body {
      padding: 30px;
      background-color: white;
    }

    .btn-submit {
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s;
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      background-color: #0b7a43;
      border-color: #0b7a43;
    }

    .form-control,
    .form-select {
      border-radius: 8px;
      padding: 12px;
      border: 1px solid #ced4da;
      transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.25rem rgba(13, 146, 79, 0.25);
    }

    .title-highlight {
      color: var(--primary-color);
    }

    .priority-high {
      color: #d35400;
    }

    .priority-urgent {
      color: var(--accent-color);
      font-weight: bold;
    }

    .success-alert {
      background-color: var(--secondary-color);
      color: white;
    }

    .error-alert {
      background-color: var(--accent-color);
      color: white;
    }
  </style>
</head>

<body>
  <div class="container main-container animate__animated animate__fadeIn">
    <?php if ($success_message): ?>
      <div
        class="alert success-alert animate__animated animate__fadeInDown position-fixed top-0 start-50 translate-middle-x mt-3"
        style="z-index: 9999;">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $success_message; ?>
      </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
      <div
        class="alert error-alert animate__animated animate__fadeInDown position-fixed top-0 start-50 translate-middle-x mt-3"
        style="z-index: 9999;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>

    <div class="text-center mb-5">
      <h2 class="fw-bold display-5 mb-3 title-highlight">Admin Announcement Panel</h2>
      <p class="text-muted">Create and manage important announcements</p>
    </div>

    <!-- Announcement Form -->
    <div id="announcementForm">
      <div class="form-card">
        <div class="form-header">
          <h4 class="mb-0"><i class="fas fa-bullhorn me-2"></i> Post an Announcement</h4>
        </div>
        <div class="form-body">
          <form method="POST" action="">
            <div class="mb-4">
              <label for="announcementTitle" class="form-label fw-bold">Title</label>
              <input type="text" name="title" class="form-control" id="announcementTitle"
                placeholder="Announcement title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>

            <div class="mb-4">
              <label for="announcementBody" class="form-label fw-bold">Announcement</label>
              <textarea class="form-control" name="message" id="announcementBody" rows="5"
                placeholder="Write announcement..." required><?php echo htmlspecialchars($message); ?></textarea>
            </div>
            <div class="text-center">
              <button type="submit" name="announcementsubmit" class="btn btn-submit">
                <i class="fas fa-share-square me-2"></i> Post Announcement
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function () {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.classList.add('animate__fadeOut');
          setTimeout(() => alert.remove(), 500);
        }, 5000);
      });
    });
  </script>
</body>

</html>