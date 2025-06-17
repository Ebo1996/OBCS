<?php
require 'init.php';
$showSuccess = false;
if (isset($_SESSION['support_success']) && $_SESSION['support_success']) {
  $showSuccess = true;
  unset($_SESSION['support_success']); // Remove it so it doesn't show again on refresh
}

include "../setup/dbconnection.php";
$sql = "SELECT * FROM announcements";
$stmt = $conn->prepare($sql);
$stmt->execute();
$notification = $stmt->get_result();
$num_notf = $notification->num_rows;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
  <style>
    body {
      min-height: 100vh;
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 1400px;
        padding: 0;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      } */

    .sidebar {
      width: 280px;
      background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
      color: white;
      padding: 25px 15px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
      z-index: 100;
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar.hidden {
      transform: translateX(-100%);
      box-shadow: none;
    }

    .sidebar .btn {
      width: 100%;
      margin-bottom: 12px;
      text-align: left;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      padding: 12px 15px;
      border-radius: 8px;
      font-weight: 500;
      letter-spacing: 0.5px;
      border: none;
      background-color: rgba(255, 255, 255, 0.1);
      color: #ecf0f1;
    }

    .sidebar .btn:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: translateX(5px);
    }

    .sidebar .btn.active {
      background-color: #3498db;
    }

    .sidebar .btn img {
      width: 22px;
      height: 22px;
      margin-right: 12px;
      filter: brightness(0) invert(1);
    }

    .sidebar.hidden .btn {
      opacity: 0;
      pointer-events: none;
    }

    .content {
      display: flex;
      align-items: center;
      justify-items: center;
      flex-grow: 1;
      padding: 30px;
      margin: auto;
      transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      width: 100%;
      max-width: 1200px;
      background-color: white;
      /* min-height: 100vh; */
    }

    .sidebar.hidden+.content {
      margin-left: 0;
    }

    .logo {
      width: 120px;
      display: block;
      margin: 0 auto 30px;
      transition: all 0.4s ease;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .sidebar.hidden .logo {
      opacity: 0;
      transform: scale(0.8);
    }

    .logout-btn {
      position: fixed;
      top: 20px;
      right: 30px;
      display: flex;
      align-items: center;
      padding: 8px 15px;
      border-radius: 20px;
      font-weight: 500;
      background-color: #e74c3c;
      color: white;
      border: none;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .logout-btn:hover {
      background-color: #c0392b;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .logout-btn img {
      width: 18px;
      height: 18px;
      margin-right: 8px;
      filter: brightness(0) invert(1);
    }

    .toggle-btn {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1000;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #2c3e50;
      color: white;
      border: none;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
    }

    .toggle-btn:hover {
      background-color: #34495e;
      transform: scale(1.1);
    }

    .welcome-message {
      margin-top: 80px;
      text-align: center;
      font-size: 2.2rem;
      color: #2c3e50;
      padding: 30px;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      border-left: 5px solid #3498db;
      animation: fadeIn 0.8s ease;
    }

    .notification-badge {
      background-color: #e74c3c;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 12px;
      margin-left: 8px;
    }
  </style>
  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('hidden');
    }
  </script>
</head>

<body>
  <div class="container">
    <button class="logout-btn" onclick="location.href='../public/logOut.php'">
      <img src="../image/logout.png" alt="Logout"> Logout
    </button>

    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <div class="sidebar">
      <img src="image/profile.png"alt="Logo" class="logo">
      <button class="btn" onclick="location.href='user.php'">
        <img src="../image/home.png" alt="Home"> HOME
      </button>
      <button class="btn" onclick="location.href='user.php?page=apply'">
        <img src="../image/apply.png" alt="Apply"> APPLY
      </button>
      <button class="btn" onclick="location.href='user.php?page=status'">
        <img src="../image/check-list.png" alt="Status"> STATUS
      </button>
      <button class="btn" onclick="location.href='user.php?page=editprofile'">
        <img src="../image/user.png" alt="Profile Setting"> PROFILE SETTING
      </button>
      <button class="btn" onclick="location.href='user.php?page=notification'">
        <img src="../image/user.png" alt=""> NOTIFICATIONS
        <!-- <span class="notification-badge"><?php echo $num_notf ?></span> -->
      </button>
      <button class="btn" onclick="location.href='user.php?page=usersupport'">
        <img src="../image/user.png" alt="Profile Setting"> user support
      </button>
    </div>

    <div class="content">
      <?php
      $sql = "SELECT * FROM users WHERE email = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $_SESSION["email"]);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();
      if ($user) {
        $_SESSION["ID"] = $user["id"];
      }
      if (isset($_GET['page'])) {
        $page = $_GET['page'];
        if ($page == "apply") {
          include 'application.php';
        } elseif ($page == "editprofile") {
          include 'editprofile.php';
        } elseif ($page == "status") {
          include 'newstatus.php';
        } elseif ($page == "notification") {
          include 'notification.php';
        } else if ($page == 'usersupport') {
          include 'usersupport.php';
        } else if ($page == 'home') {
          include 'home.php';
        } else {
          echo "<h3>Page Not Found</h3>";
        }
      } else {
        include 'home.php';
      }
      ?>
    </div>
  </div>
  <?php if ($showSuccess): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      });
    </script>
  <?php endif; ?>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-success">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="successModalLabel">Success</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          Your support request was submitted successfully!
        </div>
      </div>
    </div>
  </div>

</body>

</html>