<?php
include "../setup/dbconnection.php";
$sql = "SELECT COUNT(*) AS total_user FROM users WHERE role = 'user'";
$result = $conn->prepare($sql);
$result->execute();
$total_users = $result->get_result()->fetch_assoc()['total_user'];

$sqlhospital = "SELECT COUNT(*) AS total_hospital FROM hospitals";
$result = $conn->prepare($sqlhospital);
$result->execute();
$total_hospital = $result->get_result()->fetch_assoc()['total_hospital'];

$sqlkebele = "SELECT COUNT(*) AS total_kebele FROM kebele_officers";
$result = $conn->prepare($sqlkebele);
$result->execute();
$total_kebele = $result->get_result()->fetch_assoc()['total_kebele'];


$sqlofficer = "SELECT COUNT(*) AS total_officers FROM officers";
$result = $conn->prepare($sqlofficer);
$result->execute();
$total_officer = $result->get_result()->fetch_assoc()['total_officers'];

$sql = "
SELECT 
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS current_month,
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS last_month
FROM users
";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$currentMonth = $data['current_month'];
$lastMonth = $data['last_month'];

// Calculate percentage increase
if ($lastMonth > 0) {
  $increaseuser = $currentMonth - $lastMonth;
} else {
  $increaseuser = $currentMonth; // 100% if users were added this month and none last month
}

$sql = "
SELECT 
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS current_month,
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS last_month
FROM hospitals
";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$currentMonth = $data['current_month'];
$lastMonth = $data['last_month'];

// Calculate percentage increase
if ($lastMonth > 0) {
  $increasehospital = $currentMonth - $lastMonth;
} else {
  $increasehospital = $currentMonth; // 100% if users were added this month and none last month
}
$sql = "
SELECT 
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS current_month,
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS last_month
FROM officers
";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$currentMonth = $data['current_month'];
$lastMonth = $data['last_month'];

// Calculate percentage increase
if ($lastMonth > 0) {
  $increaseofficer = $currentMonth - $lastMonth;
} else {
  $increaseofficer = $currentMonth; // 100% if users were added this month and none last month
}
$sql = "
SELECT 
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS current_month,
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS last_month
FROM kebele_officers
";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$currentMonth = $data['current_month'];
$lastMonth = $data['last_month'];

// Calculate percentage increase
if ($lastMonth > 0) {
  $increasekebele = $currentMonth - $lastMonth;
} else {
  $increasekebele = $currentMonth; // 100% if users were added this month and none last month
}

$sql = "SELECT COUNT(*) AS female_user FROM users WHERE gender = 'female'";
$result = $conn->prepare($sql);
$result->execute();
$femaleuser = $result->get_result()->fetch_assoc()["female_user"];

$sql = "SELECT COUNT(*) AS male_user FROM users WHERE gender = 'male'";
$result = $conn->prepare($sql);
$result->execute();
$maleuser = $result->get_result()->fetch_assoc()["male_user"];


$sqlrecent = "SELECT * FROM account_support WHERE status = 'unreplied' ORDER BY created_at DESC LIMIT 4 ";
$stmt = $conn->prepare($sqlrecent);
$stmt->execute();
$recent_support = $stmt->get_result();





?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Birth Certificate System - Admin Dashboard</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
    }

    body {
      background-color: var(--light-bg);
      color: var(--primary-color);
    }

    .sidebar {
      background-color: var(--primary-color);
      color: white;
      height: 100vh;
      position: fixed;
      width: 250px;
      transition: all 0.3s;
    }

    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
      border-radius: 5px;
      transition: all 0.3s;
      margin: 5px 10px;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
      background-color: var(--secondary-color);
    }

    .sidebar-menu i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .main-content {
      /* margin-left: 450px; */
      padding: 20px;
      transition: all 0.3s;
    }

    .stat-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
      height: 100%;
      border-left: 5px solid var(--secondary-color);
    }

    .stat-card.app-officers {
      border-left-color: var(--accent-color);
    }

    .stat-card.kebele-officers {
      border-left-color: #3498db;
    }

    .stat-card.total-users {
      border-left-color: #f39c12;
    }

    .stat-card h3 {
      font-size: 14px;
      color: #7f8c8d;
      margin-bottom: 10px;
    }

    .stat-card .value {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .stat-card .change {
      font-size: 12px;
      color: #27ae60;
    }

    .chart-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      height: 100%;
    }

    .activity-card,
    .tickets-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      height: 100%;
    }

    .activity-item,
    .ticket-item {
      display: flex;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }

    .activity-item:last-child,
    .ticket-item:last-child {
      border-bottom: none;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--light-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      color: var(--secondary-color);
    }

    .ticket-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--light-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      color: var(--accent-color);
    }

    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }

    .status-resolved {
      background-color: #d4edda;
      color: #155724;
    }

    .action-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
      height: 100%;
    }

    .action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .action-card i {
      font-size: 24px;
      color: var(--secondary-color);
      margin-bottom: 10px;
    }

    .margin_left1 {
      margin-right: 10rem;
    }

    /* Mobile responsiveness */
    @media (max-width: 992px) {
      .sidebar {
        width: 0;
        overflow: hidden;
      }

      .sidebar.active {
        width: 250px;
        z-index: 1000;
      }

      .main-content {
        margin-left: 0;
      }

      .menu-toggle {
        display: block !important;
      }
    }

    .menu-toggle {
      display: none;
      font-size: 1.5rem;
      cursor: pointer;
      margin-right: 15px;
    }
  </style>
</head>

<body>
  <div class="d-flex  ">


    <!-- Main Content -->
    <div class="main-content margin_left " id="main-content">
      <!-- Top Navigation -->
      <nav class="navbar navbar-expand-lg mb-4 p-0">
        <div class="container-fluid p-0">
          <span class="menu-toggle me-3" id="menuToggle"><i class="fas fa-bars"></i></span>
          <h1 class="h4 mb-0 flex-grow-1">Dashboard Overview</h1>

        </div>
      </nav>

      <!-- Stats Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
          <div class="stat-card">
            <h3>Total Hospitals</h3>
            <div class="value"><?php echo $total_hospital ?></div>
            <div class="change">+ <?php echo $increasehospital ?> this month</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="stat-card app-officers">
            <h3>Application Officers</h3>
            <div class="value"><?php echo $total_officer ?></div>
            <div class="change">+ <?php echo $increaseofficer ?> this month</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="stat-card kebele-officers">
            <h3>Kebele Officers</h3>
            <div class="value"><?php echo $total_kebele ?></div>
            <div class="change">+<?php echo $increasekebele ?> this month</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="stat-card total-users">
            <h3>Total Users</h3>
            <div class="value"><?php echo $total_users ?></div>
            <div class="change">+<?Php echo $increaseuser ?> this month</div>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="row g-4 mb-4">
        <div class="col-lg-6">
          <div class="chart-card">
            <h2 class="h5 mb-4">User Gender Distribution</h2>
            <canvas id="genderChart"></canvas>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="chart-card">
            <h2 class="h5 mb-4">user registered (Last 6 Months)</h2>
            <canvas id="requestsChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Activity and Tickets Row -->
      <div class="row g-4 mb-4">
        <div class="col-lg-8">
          <div class="activity-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h2 class="h5 mb-0">Recent Activity Log</h2>
              <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="activity-item">
              <div class="activity-icon"><i class="fas fa-user"></i></div>
              <div class="flex-grow-1">
                <h4 class="h6 mb-1">New User Registration</h4>
                <p class="mb-0 text-muted small">Abebe Kebede registered for birth certificate</p>
              </div>
              <div class="text-muted small">10 min ago</div>
            </div>
            <div class="activity-item">
              <div class="activity-icon"><i class="fas fa-check"></i></div>
              <div class="flex-grow-1">
                <h4 class="h6 mb-1">Certificate Approved</h4>
                <p class="mb-0 text-muted small">Certificate #BC-2023-58742 approved by Kebele officer</p>
              </div>
              <div class="text-muted small">25 min ago</div>
            </div>
            <div class="activity-item">
              <div class="activity-icon"><i class="fas fa-hospital"></i></div>
              <div class="flex-grow-1">
                <h4 class="h6 mb-1">New Hospital Added</h4>
                <p class="mb-0 text-muted small">St. Paul Hospital registered in the system</p>
              </div>
              <div class="text-muted small">1 hour ago</div>
            </div>
            <div class="activity-item">
              <div class="activity-icon"><i class="fas fa-user-shield"></i></div>
              <div class="flex-grow-1">
                <h4 class="h6 mb-1">New Officer Added</h4>
                <p class="mb-0 text-muted small">Kebele officer Alemitu Fekadu added to Addis Ketema</p>
              </div>
              <div class="text-muted small">2 hours ago</div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="tickets-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h2 class="h5 mb-0">Recent Support Tickets</h2>
              <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <?php if ($recent_support->num_rows > 0) {
              while ($user_support = $recent_support->fetch_assoc()) {
                if ($user_support['status'] == 'unreplied') {

                  ?>
                  <div class="ticket-item">
                    <div class="flex-grow-1">
                      <h4 class="h6 mb-1"><?php echo $user_support['subject'] ?></h4>
                      <?php $user_id = $user_support['user_id'];
                      $sql = "SELECT * FROM users WHERE id = ?";
                      $stmt = $conn->prepare($sql);
                      $stmt->bind_param("i", $user_id);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $user = $result->fetch_assoc();
                      $full_name = $user['first_name'] . ' ' . $user['middle_name'];
                      ?>
                      <p class="mb-0 text-muted small">From: <?php echo $full_name ?></p>
                    </div>
                    <span class="badge status-pending"><?php echo $user_support['status'] ?></span>
                  </div>
                <?php }
              }
            } ?>
            <!-- <div class="ticket-item">
                            <div class="ticket-avatar">M</div>
                            <div class="flex-grow-1">
                                <h4 class="h6 mb-1">Document Upload Issue</h4>
                                <p class="mb-0 text-muted small">From: Meseret Abebe</p>
                            </div>
                            <span class="badge status-pending">Pending</span>
                        </div>
                        <div class="ticket-item">
                            <div class="ticket-avatar">T</div>
                            <div class="flex-grow-1">
                                <h4 class="h6 mb-1">Payment Problem</h4>
                                <p class="mb-0 text-muted small">From: Tewodros Bekele</p>
                            </div>
                            <span class="badge status-resolved">Resolved</span>
                        </div>
                        <div class="ticket-item">
                            <div class="ticket-avatar">E</div>
                            <div class="flex-grow-1">
                                <h4 class="h6 mb-1">Email Not Received</h4>
                                <p class="mb-0 text-muted small">From: Eden Teshome</p>
                            </div>
                            <span class="badge status-resolved">Resolved</span>
                        </div> -->
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="row g-4">
        <div class="col-6 col-md-3">
          <div class="action-card">
            <i class="fas fa-plus-circle"></i>
            <h3 class="h6">Add Hospital</h3>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="action-card">
            <i class="fas fa-user-plus"></i>
            <h3 class="h6">Add Officer</h3>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="action-card">
            <i class="fas fa-file-export"></i>
            <h3 class="h6">Generate Report</h3>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="action-card">
            <i class="fas fa-bell"></i>
            <h3 class="h6">Send Notification</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Toggle sidebar on mobile
    document.getElementById('menuToggle').addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('active');
    });

    // Initialize charts
    document.addEventListener('DOMContentLoaded', function () {
      // Gender Distribution Chart

      const genderCtx = document.getElementById('genderChart').getContext('2d');
      const genderChart = new Chart(genderCtx, {
        type: 'doughnut',
        data: {
          labels: ['Male', 'Female'],
          datasets: [{
            data: [<?php echo $maleuser ?>, <?php echo $femaleuser ?>],
            backgroundColor: [
              '#3498db',
              '#e83e8c'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: false,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });

      // Requests Chart
      const requestsCtx = document.getElementById('requestsChart').getContext('2d');
      const requestsChart = new Chart(requestsCtx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'Certificate Requests',
            data: [1250, 1900, 1700, 2100, 2400, 2800],
            backgroundColor: 'rgba(13, 146, 79, 0.1)',
            borderColor: 'rgba(13, 146, 79, 1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
          }]
        },
        options: {
          responsive: false,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
  </script>
</body>

</html>