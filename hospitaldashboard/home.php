<?php
session_start();
require_once '../setup/dbconnection.php';

// Check if user is logged in
if (!isset($_SESSION['hospital_id'])) {
  header("Location: login.php");
  exit();
}

// Get hospital information
$hospital_id = $_SESSION['hospital_id'];
$sql = "SELECT * FROM hospitals WHERE hospital_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$hospital = $stmt->get_result()->fetch_assoc();

if (!$hospital) {
  die("Hospital not found");
}

// Get recent birth records
$sql = "SELECT * FROM birth_records WHERE hospital_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$recent_records = $stmt->get_result();

// Get statistics
$stats = [
  'total_births' => 0,
  'monthly_births' => 0,
  'pending_certs' => 0,
  'gender_stats' => ['Male' => 0, 'Female' => 0],
  'today_stats' => ['Male' => 0, 'Female' => 0]
];

// Total births
$sql = "SELECT COUNT(*) as total FROM birth_records WHERE hospital_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stats['total_births'] = $result['total'];

// Monthly births
$sql = "SELECT COUNT(*) as monthly FROM birth_records 
        WHERE hospital_id = ? AND MONTH(dob) = MONTH(CURRENT_DATE()) 
        AND YEAR(dob) = YEAR(CURRENT_DATE())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stats['monthly_births'] = $result['monthly'];

// Pending certifications
// $sql = "SELECT COUNT(*) as pending FROM birth_records 
//         WHERE hospital_id = ? AND certification_status = 'pending'";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $hospital_id);
// $stmt->execute();
// $result = $stmt->get_result()->fetch_assoc();
// $stats['pending_certs'] = $result['pending'];

// Gender statistics
$sql = "SELECT gender, COUNT(*) as count FROM birth_records 
        WHERE hospital_id = ? GROUP BY gender";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $stats['gender_stats'][$row['gender']] = $row['count'];
}

// Today's births
$sql = "SELECT gender, COUNT(*) as count FROM birth_records 
        WHERE hospital_id = ? AND DATE(dob) = CURDATE() 
        GROUP BY gender";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $stats['today_stats'][$row['gender']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Birth Records Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
    }

    .main-content {
      background-color: var(--light-bg);
      min-height: 100vh;
      margin-left: 280px;
      padding: 20px;
      transition: all 0.3s;
    }

    .dashboard-header {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .stat-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: all 0.3s;
      border-left: 4px solid var(--primary-color);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-card.primary {
      border-left-color: var(--primary-color);
    }

    .stat-card.success {
      border-left-color: var(--secondary-color);
    }

    .stat-card.danger {
      border-left-color: var(--accent-color);
    }

    .stat-icon {
      font-size: 2rem;
      color: var(--primary-color);
      margin-bottom: 15px;
    }

    .stat-number {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 5px;
    }

    .stat-title {
      color: #6c757d;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .recent-records {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .table-custom thead th {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 12px 15px;
    }

    .table-custom tbody tr {
      transition: all 0.3s;
    }

    .table-custom tbody tr:hover {
      background-color: rgba(44, 62, 80, 0.05);
    }

    .btn-custom {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      transition: all 0.3s;
    }

    .btn-custom:hover {
      background-color: #1a252f;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-success-custom {
      background-color: var(--secondary-color);
    }

    .btn-success-custom:hover {
      background-color: #0a7a40;
    }

    @media (max-width: 992px) {
      .main-content {
        margin-left: 0;
        padding: 15px;
      }
    }
  </style>
</head>

<body>
  <div class="main-content">
    <div class="dashboard-header">
      <h2 class="mb-0"><i class="fas fa-baby me-2"></i>Birth Records Dashboard</h2>
      <p class="text-muted mb-0"><?= htmlspecialchars($hospital['name']) ?></p>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="stat-card primary">
          <div class="stat-icon">
            <i class="fas fa-baby-carriage"></i>
          </div>
          <div class="stat-number"><?= number_format($stats['total_births']) ?></div>
          <div class="stat-title">Total Births Recorded</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card success">
          <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
          </div>
          <div class="stat-number"><?= number_format($stats['monthly_births']) ?></div>
          <div class="stat-title">Births This Month</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card danger">
          <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
          </div>
          <div class="stat-number"><?= number_format($stats['pending_certs']) ?></div>
          <div class="stat-title">Pending Certifications</div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-8">
        <div class="recent-records">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fas fa-list me-2"></i>Recent Birth Records</h4>
            <a href="add_record.php" class="btn btn-custom btn-sm">
              <i class="fas fa-plus me-1"></i> New Record
            </a>
          </div>

          <div class="table-responsive">
            <table class="table table-custom table-hover">
              <thead>
                <tr>
                  <th>Record ID</th>
                  <th>Child's Name</th>
                  <th>Birth Date</th>
                  <th>Gender</th>
                  <th>Doctor</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($recent_records->num_rows > 0): ?>
                  <?php while ($record = $recent_records->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($record['record_id']) ?></td>
                      <td><?= htmlspecialchars($record['child_name']) ?></td>
                      <td><?= date('M j, Y', strtotime($record['dob'])) ?></td>
                      <td><?= htmlspecialchars($record['gender']) ?></td>
                      <td><?= htmlspecialchars($record['nameOfDoctor']) ?></td>
                      <td>
                        <a href="view_record.php?id=<?= $record['record_id'] ?>" class="btn btn-sm btn-outline-primary">
                          <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted">No recent records found</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="text-end mt-3">
            <a href="all_records.php" class="text-primary">View All Records <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="recent-records">
          <h4 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Birth Statistics</h4>
          <div class="text-center mb-4">
            <canvas id="birthChart" height="200"></canvas>
          </div>
          <div class="mt-4">
            <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Today's Births</h5>
            <div class="list-group">
              <div class="list-group-item border-0 py-2">
                <div class="d-flex justify-content-between">
                  <span>Male Births</span>
                  <strong><?= $stats['today_stats']['Male'] ?></strong>
                </div>
              </div>
              <div class="list-group-item border-0 py-2">
                <div class="d-flex justify-content-between">
                  <span>Female Births</span>
                  <strong><?= $stats['today_stats']['Female'] ?></strong>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-4">
            <a href="generate_report.php" class="btn btn-success-custom w-100">
              <i class="fas fa-file-export me-2"></i> Generate Monthly Report
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const ctx = document.getElementById('birthChart');
      if (ctx) {
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ['Male', 'Female'],
            datasets: [{
              data: [<?= $stats['gender_stats']['Male'] ?>, <?= $stats['gender_stats']['Female'] ?>],
              backgroundColor: [
                '#3498db',
                '#e83e8c'
              ],
              borderWidth: 0
            }]
          },
          options: {
            cutout: '70%',
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        });
      }
    });
  </script>
</body>

</html>