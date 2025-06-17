<?php
session_start();
include '../setup/dbconnection.php';
$hospital_id = $_SESSION['hospital_id'];
$msg_sql = "SELECT * FROM hospital_messages WHERE hospital_id = ? ORDER BY sent_at DESC";
$msg_stmt = $conn->prepare($msg_sql);
$msg_stmt->bind_param("i", $hospital_id);
$msg_stmt->execute();
$hospital_msgs = $msg_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Notifications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
    }

    /* Specific container for this page to prevent conflicts */
    .page-specific-container {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
      width: 100%;
      min-height: 100vh;
    }

    /* Wrapper to center and constrain width */
    .content-wrapper {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .notification-header {
      color: var(--primary-color);
      border-bottom: 3px solid var(--secondary-color);
      padding-bottom: 0.5rem;
      margin-bottom: 2rem;
    }

    .message-card {
      border-left: 4px solid var(--secondary-color);
      transition: transform 0.2s;
      margin-bottom: 1.5rem;
      border-radius: 0.375rem;
    }

    .message-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .message-header {
      background-color: var(--primary-color);
      color: white;
      border-radius: 0.375rem 0.375rem 0 0 !important;
    }

    .message-time {
      font-size: 0.8rem;
      color: #adb5bd;
    }

    .message-badge {
      background-color: var(--accent-color);
    }

    .card-text {
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
    }

    .expanded {
      display: block !important;
    }

    .see-more-btn {
      color: var(--secondary-color);
      cursor: pointer;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
    }

    .see-more-btn:hover {
      text-decoration: underline;
    }

    .empty-state {
      background-color: white;
      border-radius: 0.5rem;
      padding: 3rem;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .empty-state-icon {
      font-size: 3rem;
      color: var(--secondary-color);
      margin-bottom: 1rem;
    }
  </style>
</head>

<body>
  <div class="page-specific-container">
    <div class="content-wrapper">
      <div class="text-center mb-5">
        <h1 class="notification-header">
          <i class="bi bi-bell-fill me-2"></i>Hospital Notifications
        </h1>
        <p class="text-muted">View all your messages and announcements in one place</p>
      </div>

      <?php if (isset($hospital_msgs) && $hospital_msgs->num_rows > 0) { ?>
        <div class="mb-4">
          <h4 class="text-muted mb-3">
            <i class="bi bi-envelope me-2"></i>Your Messages
          </h4>

          <?php while ($message = $hospital_msgs->fetch_assoc()) { ?>
            <div class="card message-card shadow-sm">
              <div class="card-header message-header d-flex justify-content-between align-items-center">
                <div>
                  <strong><?= htmlspecialchars($message["subject"]) ?></strong>
                </div>
                <div>
                  <span class="message-time me-2">
                    <i class="bi bi-clock me-1"></i><?= htmlspecialchars($message["sent_at"]) ?>
                  </span>
                  <span class="badge message-badge">Admin</span>
                </div>
              </div>
              <div class="card-body">
                <p class="card-text" id="msg-<?= $message['id'] ?>">
                  <?= nl2br(htmlspecialchars($message["message"])) ?>
                </p>
                <button class="btn btn-link see-more-btn p-0" onclick="toggleText('msg-<?= $message['id'] ?>', this)">
                  <i class="bi bi-chevron-down me-1"></i>Read more
                </button>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <div class="empty-state">
          <div class="empty-state-icon">
            <i class="bi bi-inbox"></i>
          </div>
          <h3 class="text-primary">No Messages Found</h3>
          <p class="text-muted">You don't have any messages or announcements yet.</p>
          <button class="btn btn-outline-primary mt-3">
            <i class="bi bi-arrow-repeat me-1"></i>Check Again
          </button>
        </div>
      <?php } ?>

      <div class="text-center mt-5 text-muted">
        <small>© <?= date('Y') ?> Hospital Management System. All rights reserved.</small>
      </div>
    </div>
  </div>

  <script>
    function toggleText(id, btn) {
      const content = document.getElementById(id);
      const icon = btn.querySelector('i');

      if (content.classList.contains('expanded')) {
        content.classList.remove('expanded');
        content.style.webkitLineClamp = "3";
        btn.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Read more';
      } else {
        content.classList.add('expanded');
        content.style.webkitLineClamp = "unset";
        btn.innerHTML = '<i class="bi bi-chevron-up me-1"></i>Show less';
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>