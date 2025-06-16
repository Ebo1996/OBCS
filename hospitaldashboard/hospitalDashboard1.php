<?php
if (isset($_GET["page"])) {
  $page = $_GET["page"];

  if ($page == "birthrecord") {
    include "birthrecord.php";
  } else if ($page == "home") {
    include "home.php";
  } else if ($page == "recordManagement") {
    include "managebirthrecordfinal.php";
  } else if ($page == "notification") {
    include "notification.php";
  } else {
    include "home.php";
  }
} else {
  include "home.php";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hospital Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    /* body {
      display: flex;
      min-height: 100vh;
      margin: 0;
      justify-content: center;
      align-items: center;
      background-color: #f8f9fa;
    } */

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      max-width: 1200px;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);

    }

    .sidebar {
      width: 250px;
      background-color: #6c757d;
      color: white;
      padding: 20px;
      transition: transform 0.5s ease, background-color 0.5s ease;
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
    }

    .sidebar.hidden {
      transform: translateX(-100%);
      background-color: rgba(108, 117, 125, 0.5);
    }

    .sidebar .btn {
      width: 100%;
      margin-bottom: 10px;
      text-align: left;
      display: flex;
      align-items: center;
      transition: opacity 0.5s ease;
    }

    .sidebar.hidden .btn {
      opacity: 0;
    }

    .sidebar .btn img {
      width: 20px;
      height: 20px;
      margin-right: 10px;
    }

    .content {
      flex-grow: 1;
      padding: 20px;
      margin-left: 250px;
      transition: margin-left 0.5s ease;
      width: 100%;
      max-width: 1000px;
    }

    .sidebar.hidden+.content {
      margin-left: 0;
    }

    .logo {
      width: 100px;
      display: block;
      margin-bottom: 20px;
      transition: opacity 0.5s ease;
    }

    .sidebar.hidden .logo {
      opacity: 0;
    }

    .logout-btn {
      position: absolute;
      top: 10px;
      right: 20px;
      display: flex;
      align-items: center;
    }

    .logout-btn img {
      width: 20px;
      height: 20px;
      margin-right: 5px;
    }

    .toggle-btn {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 1000;
    }

    .animated-card {
      overflow: hidden;
      border-radius: 15px;
      transform: translateY(30px);
      opacity: 0;
      animation: fadeInUp 0.8s ease forwards;
    }

    .animated-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .animated-card:hover img {
      transform: scale(1.1);
    }

    @keyframes fadeInUp {
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .animated-delay-1 {
      animation-delay: 0.3s;
    }

    .animated-delay-2 {
      animation-delay: 0.6s;
    }

    .animated-delay-3 {
      animation-delay: 0.9s;
    }

    .animated-delay-4 {
      animation-delay: 1.2s;
    }

    /* Animated H4 Heading */
    .animated-title {
      font-weight: bold;
      animation: slideFadeIn 1.2s ease-out forwards;
      opacity: 0;
      transform: translateY(-20px);
      transition: all 0.4s ease-in-out;
    }

    @keyframes slideFadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .dashboard_container {
      display: flex;
      flex-direction: column;
    }
  </style>
  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('hidden');
    }
  </script>
</head>

<body>

  <div class="dashboard_container">
    <button class="btn btn-secondary toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="sidebar">
      <img src="image/hospital.png" alt="Logo" class="logo" />
      <a onclick='location.href="hospitalDashboard.php?page=home"' class="btn btn-primary">
        <img src="image/apply.png" alt="Home" /> HOME
      </a>
      <a onclick='location.href="hospitalDashboard.php?page=birthrecord"' class="btn btn-primary">
        <img src="image/apply.png" alt="Home" /> APPLY FOR BIRTH CERTIFICATE
      </a>
      <a onclick='location.href="hospitalDashboard.php?page=recordManagement"' class="btn btn-primary">
        <img src="image/mngmt.png" alt="Apply" /> BIRTH CERTIFICATE MANAGEMENT
      </a>
      <a onclick='location.href="hospitalDashboard.php?page=notification"' class="btn btn-primary">
        <img src="image/mngmt.png" alt="Apply" /> NOTIFICATION
      </a>
    </div>

    <!-- -->
  </div>

</body>

</html>