<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KEBELE Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      min-height: 100vh;
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
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
    }

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
      /* margin-left: 280px; */
      transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      width: 100%;
      /* max-width: 1200px; */
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
      position: absolute;
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


    /* Kebele Dashboard Styles */
    .kebele-dashboard-container {
      padding: 25px;
      margin-left: 280px;
      transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      background-color: var(--light-bg);
      min-height: 100vh;
    }

    .kebele-dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .kebele-dashboard-title {
      color: var(--primary-color);
      font-size: 1.8rem;
      font-weight: 700;
      margin: 0;
    }

    .kebele-date-selector {
      background-color: white;
      padding: 10px 15px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .kebele-date-selector:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .kebele-date-display {
      font-weight: 500;
      color: var(--primary-color);
    }

    /* Stats Cards */
    .kebele-stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .kebele-stat-card {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      gap: 15px;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .kebele-stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .kebele-stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      background-color: var(--primary-color);
    }

    .kebele-stat-content {
      flex: 1;
    }

    .kebele-stat-content h3 {
      font-size: 0.95rem;
      color: #7f8c8d;
      margin-bottom: 5px;
      font-weight: 500;
    }

    .kebele-stat-number {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 5px;
    }

    .kebele-stat-trend {
      font-size: 0.8rem;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .kebele-stat-trend.up {
      color: var(--secondary-color);
    }

    .kebele-stat-trend.down {
      color: var(--accent-color);
    }

    /* Charts Section */
    .kebele-charts-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    .kebele-chart-container {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .kebele-chart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .kebele-chart-header h3 {
      font-size: 1.1rem;
      color: var(--primary-color);
      margin: 0;
      font-weight: 600;
    }

    .kebele-chart-actions {
      display: flex;
      gap: 5px;
    }

    .kebele-chart-action-btn {
      border: none;
      background-color: #ecf0f1;
      color: #7f8c8d;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .kebele-chart-action-btn.active {
      background-color: var(--primary-color);
      color: white;
    }

    .kebele-chart-select {
      border: 1px solid #ddd;
      border-radius: 6px;
      padding: 5px 10px;
      font-size: 0.85rem;
      color: var(--primary-color);
    }

    .kebele-chart-wrapper {
      height: 300px;
      position: relative;
    }

    /* Bottom Row */
    .kebele-bottom-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .kebele-activity-container,
    .kebele-actions-container {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .kebele-section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .kebele-section-header h3 {
      font-size: 1.1rem;
      color: var(--primary-color);
      margin: 0;
      font-weight: 600;
    }

    .kebele-view-all {
      color: var(--primary-color);
      font-size: 0.9rem;
      text-decoration: none;
      font-weight: 500;
    }

    .kebele-view-all:hover {
      text-decoration: underline;
    }

    /* Activity List */
    .kebele-activity-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .kebele-activity-item {
      display: flex;
      align-items: flex-start;
      gap: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .kebele-activity-item:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .kebele-activity-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 0.9rem;
    }

    .kebele-activity-icon.approved {
      background-color: var(--secondary-color);
    }

    .kebele-activity-icon.new {
      background-color: #3498db;
    }

    .kebele-activity-icon.pending {
      background-color: #f39c12;
    }

    .kebele-activity-icon.rejected {
      background-color: var(--accent-color);
    }

    .kebele-activity-content {
      flex: 1;
    }

    .kebele-activity-content p {
      margin: 0;
      color: var(--primary-color);
      font-size: 0.95rem;
    }

    .kebele-activity-content small {
      color: #95a5a6;
      font-size: 0.8rem;
    }

    /* Quick Actions */
    .kebele-actions-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
    }

    .kebele-action-card {
      border: none;
      background: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      padding: 15px 10px;
      border-radius: 8px;
      transition: all 0.2s;
      cursor: pointer;
    }

    .kebele-action-card:hover {
      background-color: #f8f9fa;
      transform: translateY(-3px);
    }

    .kebele-action-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: white;
    }

    .kebele-action-icon.primary {
      background-color: var(--primary-color);
    }

    .kebele-action-icon.secondary {
      background-color: #3498db;
    }

    .kebele-action-icon.accent {
      background-color: var(--accent-color);
    }

    .kebele-action-icon.dark {
      background-color: #34495e;
    }

    .kebele-action-icon.success {
      background-color: var(--secondary-color);
    }

    .kebele-action-icon.warning {
      background-color: #f39c12;
    }

    .kebele-action-card span {
      font-size: 0.85rem;
      color: var(--primary-color);
      text-align: center;
      font-weight: 500;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {

      .kebele-charts-row,
      .kebele-bottom-row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 992px) {
      .kebele-dashboard-container {
        margin-left: 0;
        padding-top: 80px;
      }

      .kebele-actions-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .kebele-stats-row {
        grid-template-columns: 1fr;
      }

      .kebele-actions-grid {
        grid-template-columns: 1fr;
      }
    }





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
  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('hidden');

      // For mobile responsiveness
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
  </script>
</head>

<body>

  <div class="container">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <div class="sidebar">
      <img src="image/office-building.png" alt="Logo" class="logo" />
      <button onclick="location.href='sidebar.php?page=home'" class="btn btn-primary active">
        <img src="image/home (2).png" alt="Home" /> HOME
      </button>
      <button onclick="location.href='sidebar.php?page=register'" class="btn btn-primary">
        <img src="image/regis.png" alt="ID Registration" /> ID REGISTRATION
      </button>
      <button onclick="location.href='sidebar.php?page=manage'" class="btn btn-primary">
        <img src="image/manage.png" alt="Manage" /> MANAGE
      </button>
      <button onclick="location.href='sidebar.php?page=message'" class="btn btn-primary">
        <img src="image/message.png" alt="Message" />MESSAGE
      </button>

    </div>

    <div class="content">
      <button class="logout-btn">
        <img src="image/logout.png" alt="Logout" /> Logout
      </button>
      <?php
      if (isset($_GET["page"])) {
        $page = $_GET["page"];

        if ($page == "home") {
          include "home.php";
        } else if ($page == "register") {
          include "idregistration.php";
        } else if ($page == "manage") {
          include "manageid.php";
        } else if ($page == "message") {
          include "message1.php";
        } else if ($page == "inbox") {
          include "inbox.php";
        } else {
          include "home.php";
        }
      }

      ?>
    </div>
  </div>

</body>

</html>