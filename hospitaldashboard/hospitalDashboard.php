<?php
if (isset($_GET["page"])) {
  $page = $_GET["page"];

  if ($page == "home") {
    include "home.php";
  } else if ($page == "birthrecord") {
    include "birthrecord.php";
  } else if ($page == "recordManagement") {
    include "managebirthrecordfinal.php";
  } else if ($page == "notification") {
    include "notification.php";
  } else {
    include "home.php";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hospital Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {}

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
      flex-grow: 1;
      padding: 30px;
      margin-left: 280px;
      transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      width: 100%;
      max-width: 1200px;
      background-color: white;
      min-height: 100vh;
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

    /* .welcome-message {
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
    } */

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.hidden {
        transform: translateX(-100%);
      }

      .sidebar.show-mobile {
        transform: translateX(0);
      }

      .content {
        margin-left: 0;
      }

      .toggle-btn {
        display: flex;
      }
    }
  </style>
</head>

<body>

  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <button class="logout-btn">
    <img src="image/logout.png" alt="Logout" /> LOGOUT
  </button>

  <div class="sidebar">
    <img src="image/hospital.png" alt="Logo" class="logo img-fluid" />
    <div class="d-flex flex-column">
      <a href="hospitalDashboard.php?page=home"' class="btn active">
        <img src="image/apply.png" alt="Home" /> HOME
      </a>
      <a href="hospitalDashboard.php?page=birthrecord"' class="btn">
        <img src="image/apply.png" alt="Birth Certificate" /> REGISTER
      </a>
      <a href="hospitalDashboard.php?page=recordManagement"' class="btn">
        <img src="image/mngmt.png" alt="Management" /> RECORDS
      </a>
      <a href="hospitalDashboard.php?page=notification"' class="btn">
        <img src="image/mngmt.png" alt="Notification" /> NOTIFICATION
      </a>
    </div>
  </div>
  <!-- Content will be loaded here from PHP includes -->
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('hidden');

      //For mobile responsiveness
      if (window.innerWidth <= 992) {
        sidebar.classList.toggle('show-mobile');
      }
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (event) {
      const sidebar = document.querySelector('.sidebar');
      const toggleBtn = document.querySelector('.toggle-btn');

      if (window.innerWidth <= 992 && !sidebar.contains(event.target) && event.target !== toggleBtn && !toggleBtn.contains(event.target)) {
        sidebar.classList.add('hidden');
        sidebar.classList.remove('show-mobile');
      }
    });

    // Highlight active button based on current page
    document.addEventListener('DOMContentLoaded', function () {
      const buttons = document.querySelectorAll('.sidebar .btn');
      const currentPage = window.location.href.split('page=')[1] || 'home';

      buttons.forEach(button => {
        const page = button.getAttribute('onclick').split('page=')[1].replace(/'/g, '');
        if (page === currentPage) {
          button.classList.add('active');
        } else {
          button.classList.remove('active');
        }
      });
    });
  </script>
</body>


</html>