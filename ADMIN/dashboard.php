<?php
include '../setup/dbconnection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar {
      width: 250px;
      background-color: var(--primary-color);
      color: white;
      padding: 20px;
      transition: all 0.3s ease;
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .sidebar.hidden {
      transform: translateX(-100%);
    }

    .sidebar .btn {
      width: 100%;
      margin-bottom: 10px;
      text-align: left;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      padding: 10px 15px;
      border-radius: 5px;
      color: white;
      background-color: rgba(255, 255, 255, 0.1);
      border: none;
    }

    .sidebar .btn:hover {
      background-color: var(--secondary-color);
      transform: translateX(5px);
    }

    .sidebar .btn i {
      margin-right: 10px;
      font-size: 1.1rem;
    }

    .sidebar.hidden .btn {
      opacity: 0;
      pointer-events: none;
    }

    .logo {
      width: 100%;
      max-width: 150px;
      display: block;
      margin: 0 auto 30px;
      transition: all 0.3s ease;
      border-radius: 50%;
      padding: 5px;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar.hidden .logo {
      opacity: 0;
    }

    .toggle-btn {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1100;
      background-color: var(--primary-color);
      color: white;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
    }

    .toggle-btn:hover {
      background-color: var(--secondary-color);
      transform: scale(1.1);
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
      transition: all 0.3s ease;
      min-height: 100vh;
      /* width: 90%; */
    }

    .sidebar.hidden+.main-content {
      margin-left: 0;
    }

    .animated-card {
      transition: all 0.3s ease;
      border: none;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .animated-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .animated-card .card-body {
      padding: 20px;
    }

    .animated-card .card-title {
      color: var(--primary-color);
      font-weight: 600;
    }

    .btn-primary {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }

    .btn-primary:hover {
      background-color: #0b7a41;
      border-color: #0b7a41;
    }

    .btn-danger {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
    }

    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .toggle-btn {
        display: block !important;
      }
    }

    .menu-toggle {
      display: none;
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1100;
    }

    .table th {
      background-color: var(--primary-color);
      color: white;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(13, 146, 79, 0.1);
    }
  </style>
</head>

<body>
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <div class="sidebar">
    <img src="images/admin.png" alt="Logo" class="logo" />
    <a href="dashboard.php?page=home" class="btn">
      <i class="fas fa-home"></i> HOME
    </a>
    <a href="dashboard.php?page=regester" class="btn">
      <i class="fas fa-hospital"></i> REGISTERING HOSPITAL
    </a>
    <a href="dashboard.php?page=manage" class="btn">
      <i class="fas fa-users-cog"></i> MANAGE USER
    </a>
    <a href="dashboard.php?page=create" class="btn">
      <i class="fas fa-user-tag"></i> CREATE ROLE
    </a>
    <a href="dashboard.php?page=announcement" class="btn">
      <i class="fas fa-bell"></i> ANNOUNCEMENT
    </a>
    <a href="dashboard.php?page=usersupport" class="btn">
      <i class="fas fa-bell"></i> USER SUPPORT
    </a>
    <a href="dashboard.php?page=hospitals" class="btn">
      <i class="fas fa-hopital"></i> HOSPITALS
    </a>
    <a href="dashboard.php?page=officers" class="btn">
      <i class="fas fa-officers"></i> OFFICERS
    </a>
    <a href="../public/logOut.php" class="btn">
      <i class="fas fa-officers"></i> LOGOUT
    </a>
  </div>

  <div class="main-content">
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
      if ($page == "home") {
        include 'home.php';
      } elseif ($page == "regester") {
        include 'registering.php';
      } elseif ($page == "manage") {
        include 'manageuser.php';
      } elseif ($page == "create") {
        include 'createrole.php';
      } elseif ($page == "announcement") {
        include 'announcement.php';
      } else if ($page == "usersupport") {
        include 'usersupport.php';
      } else if ($page == "hospitals") {
        include 'hospital.php';
      } else if ($page == "officers") {
        include 'officers.php';
      } else {
        echo "<div class='alert alert-danger'>Page Not Found</div>";
      }
    } else {
      include 'home.php';
    }
    ?>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('hidden');

      // For mobile view
      if (window.innerWidth <= 992) {
        sidebar.classList.toggle('active');
      }
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
      const sidebar = document.querySelector('.sidebar');
      const toggleBtn = document.querySelector('.toggle-btn');

      if (window.innerWidth <= 992 && !sidebar.contains(e.target) && e.target !== toggleBtn) {
        sidebar.classList.remove('active');
      }
    });
  </script>
</body>

</html>