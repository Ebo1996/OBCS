<?php
include '../setup/dbconnection.php';


// Fetch user-specific messages
$msg_sql = "SELECT * FROM messages  ORDER BY sent_at DESC LIMIT 3";
$msg_stmt = $conn->prepare($msg_sql);
$msg_stmt->execute();
$user_msgs = $msg_stmt->get_result();

// Fetch announcements
$ann_sql = "SELECT * FROM announcements ORDER BY posted_at DESC";
$ann_stmt = $conn->prepare($ann_sql);
$ann_stmt->execute();
$announcements = $ann_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>User Notifications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .message-card {
      border-left: 4px solid #0d6efd;
      transition: transform 0.2s;
    }

    .message-card:hover {
      transform: translateY(-2px);
    }

    .announcement-card {
      border-left: 4px solid #ffc107;
      transition: transform 0.2s;
    }

    .announcement-card:hover {
      transform: translateY(-2px);
    }

    .card-text {
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
    }

    .see-more {
      color: #0d6efd;
      cursor: pointer;
      font-size: 0.9rem;
      text-decoration: none;
    }

    .see-more:hover {
      text-decoration: underline;
    }

    .narrow-container {
      max-width: 800px;
    }

    .time-badge {
      font-size: 0.75rem;
      background-color: #f8f9fa;
      color: #6c757d;
    }

    .empty-state {
      height: 60vh;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container narrow-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-primary mb-0"><i class="bi bi-bell-fill me-2"></i>Notifications</h2>
      <span class="badge bg-primary rounded-pill"><?= $user_msgs->num_rows + $announcements->num_rows ?> unread</span>
    </div>

    <?php if ($user_msgs->num_rows > 0 || $announcements->num_rows > 0): ?>
      <?php if ($user_msgs->num_rows > 0): ?>
        <div class="d-flex align-items-center mb-3">
          <h5 class="text-muted mb-0"><i class="bi bi-envelope me-2"></i>Personal Messages</h5>
          <span class="badge bg-info ms-2"><?= $user_msgs->num_rows ?></span>
        </div>

        <?php while ($message = $user_msgs->fetch_assoc()): ?>
          <div class="card message-card mb-3 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <div>
                <span class="badge bg-info me-2">Admin</span>
                <strong><?= htmlspecialchars($message["title"]) ?></strong>
              </div>
              <span class="time-badge rounded-pill px-2"><i
                  class="bi bi-clock me-1"></i><?= htmlspecialchars($message["sent_at"]) ?></span>
            </div>
            <div class="card-body">
              <p class="card-text" id="msg<?= $message['id'] ?>">
                <?= nl2br(htmlspecialchars($message["body"])) ?>
              </p>
              <a class="see-more" onclick="toggleText('msg<?= $message['id'] ?>', this)">Show more</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>

      <?php if ($announcements->num_rows > 0): ?>
        <div class="d-flex align-items-center mb-3 mt-4">
          <h5 class="text-muted mb-0"><i class="bi bi-megaphone me-2"></i>Announcements</h5>
          <span class="badge bg-warning text-dark ms-2"><?= $announcements->num_rows ?></span>
        </div>

        <?php while ($announcement = $announcements->fetch_assoc()): ?>
          <div class="card announcement-card mb-3 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <div>
                <span class="badge bg-warning text-dark me-2">Announcement</span>
                <strong><?= htmlspecialchars($announcement["title"]) ?></strong>
              </div>
              <span class="time-badge rounded-pill px-2"><i
                  class="bi bi-clock me-1"></i><?= htmlspecialchars($announcement["posted_at"]) ?></span>
            </div>
            <div class="card-body">
              <p class="card-text" id="ann<?= $announcement['id'] ?>">
                <?= nl2br(htmlspecialchars($announcement["body"])) ?>
              </p>
              <a class="see-more" onclick="toggleText('ann<?= $announcement['id'] ?>', this)">Show more</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    <?php else: ?>
      <div class="empty-state d-flex flex-column justify-content-center align-items-center text-center">
        <div class="bg-white p-5 rounded-3 shadow-sm">
          <i class="bi bi-envelope-open display-4 text-muted mb-3"></i>
          <h3 class="text-primary mb-2">No notifications yet</h3>
          <p class="text-muted">You don't have any messages or announcements at this time.</p>
          <button class="btn btn-outline-primary mt-3">
            <i class="bi bi-arrow-repeat me-1"></i> Check again
          </button>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script>
    function toggleText(id, btn) {
      const content = document.getElementById(id);
      const isCollapsed = content.style.webkitLineClamp === "3" || !content.style.webkitLineClamp;

      if (isCollapsed) {
        content.style.webkitLineClamp = "unset";
        btn.textContent = "Show less";
      } else {
        content.style.webkitLineClamp = "3";
        btn.textContent = "Show more";
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>