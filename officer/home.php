<?php
include "../setup/dbconnection.php";
$sql = "SELECT * FROM users WHERE role = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$addmin = $result->fetch_assoc();

$sqlmale = "SELECT gender, COUNT(*) as male_gender  FROM certificates WHERE gender = 'Male'";
$stmt = $conn->prepare($sqlmale);
$stmt->execute();
$result = $stmt->get_result();
$male = $result->fetch_assoc();
$sqlfemale = "SELECT gender , COUNT(*) as female_gender FROM certificates WHERE gender = 'Female'";
$stmt = $conn->prepare($sqlfemale);
$stmt->execute();
$result = $stmt->get_result();
$female = $result->fetch_assoc();

$sql = "SELECT COUNT(*) AS totalcertificate FROM Certificates";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$total = $result->fetch_assoc();
$pendingsql = "SELECT COUNT(*) AS Pending FROM applications";
$stmt = $conn->prepare($pendingsql);
$stmt->execute();
$result = $stmt->get_result();
$pending = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Certificate System - Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Scoped styles to prevent conflicts */
        .home-content {
            /* margin-left: 280px; */
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 25px;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .home-main-sidebar.hidden~.home-content {
            margin-left: 0;
        }

        .home-card {
            border-radius: 12px;
            border: none;
            /* box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); */
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .home-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .home-card-icon {
            font-size: 2rem;
            opacity: 0.8;
            position: absolute;
            right: 20px;
            top: 20px;
            color: rgba(255, 255, 255, 0.3);
        }

        .home-card-primary {
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            color: white;
        }

        .home-card-success {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
        }

        .home-card-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .home-card-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .home-header {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .home-profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3498db;
        }

        .home-chart-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .home-activity-item {
            border-left: 3px solid #3498db;
            padding-left: 15px;
            margin-bottom: 15px;
        }

        .home-activity-item:last-child {
            margin-bottom: 0;
        }

        .home-badge-success {
            background: rgba(46, 204, 113, 0.2);
            color: #27ae60;
        }

        .home-badge-warning {
            background: rgba(243, 156, 18, 0.2);
            color: #e67e22;
        }

        .home-badge-danger {
            background: rgba(231, 76, 60, 0.2);
            color: #c0392b;
        }
    </style>
</head>

<body>
    <main class="home-content">
        <div class="home-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Admin Dashboard</h2>
                <div class="d-flex align-items-center">
                    <span class="me-3">Welcome, <?php echo $addmin["first_name"] . " " . $addmin["last_name"] ?></span>
                    <img src="../assets//uploads/1746019401_photo_2025-04-26_22-11-24.jpg" class="home-profile-img" alt="Profile">
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="home-card home-card-primary">
                    <div class="card-body position-relative">
                        <i class="fas fa-certificate home-card-icon"></i>
                        <h6 class="text-white-50">Total Certificates</h6>
                        <h3 class="text-white"><?php echo $total["totalcertificate"] ?></h3>
                        <small class="text-white-50"><i class="fas fa-arrow-up"></i> 5.2% from last month</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="home-card home-card-success">
                    <div class="card-body position-relative">
                        <i class="fas fa-calendar-alt home-card-icon"></i>
                        <h6 class="text-white-50">This Month</h6>
                        <h3 class="text-white">1,243</h3>
                        <small class="text-white-50"><i class="fas fa-arrow-up"></i> 12.7% from last month</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="home-card home-card-warning">
                    <div class="card-body position-relative">
                        <i class="fas fa-clock home-card-icon"></i>
                        <h6 class="text-white-50">Pending Requests</h6>
                        <h3 class="text-white"><?php echo $pending["Pending"] ?></h3>
                        <small class="text-white-50"><i class="fas fa-arrow-down"></i> 3.4% from last month</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="home-card home-card-danger">
                    <div class="card-body position-relative">
                        <i class="fas fa-times-circle home-card-icon"></i>
                        <h6 class="text-white-50">Rejected</h6>
                        <h3 class="text-white">23</h3>
                        <small class="text-white-50"><i class="fas fa-arrow-up"></i> 1.2% from last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8 mb-3">
                <div class="home-chart-card">
                    <h5 class="mb-4">Certificates Issued (Last 12 Months)</h5>
                    <canvas id="yearlyChart" height="250"></canvas>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="home-chart-card">
                    <h5 class="mb-4">Gender Distribution</h5>
                    <canvas id="genderChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Top Locations -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="home-chart-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Recent Activity</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div>
                        <div class="home-activity-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>New certificate issued</strong>
                                    <p class="mb-0 text-muted">Certificate #BC-2023-12456</p>
                                </div>
                                <small class="text-muted">2 mins ago</small>
                            </div>
                        </div>
                        <div class="home-activity-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Registration approved</strong>
                                    <p class="mb-0 text-muted">For Baby Smith (ID: 7890)</p>
                                </div>
                                <small class="text-muted">25 mins ago</small>
                            </div>
                        </div>
                        <div class="home-activity-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Certificate printed</strong>
                                    <p class="mb-0 text-muted">Certificate #BC-2023-12455</p>
                                </div>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                        </div>
                        <div class="home-activity-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>New user registered</strong>
                                    <p class="mb-0 text-muted">Dr. Johnson (Hospital Admin)</p>
                                </div>
                                <small class="text-muted">3 hours ago</small>
                            </div>
                        </div>
                        <div class="home-activity-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Request rejected</strong>
                                    <p class="mb-0 text-muted">Incomplete documentation</p>
                                </div>
                                <small class="text-muted">5 hours ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="home-chart-card">
                    <h5 class="mb-4">Top Registration Locations</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Count</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>City General Hospital</td>
                                    <td>3,456</td>
                                    <td><span class="badge home-badge-success">+12%</span></td>
                                </tr>
                                <tr>
                                    <td>Women & Children Hospital</td>
                                    <td>2,789</td>
                                    <td><span class="badge home-badge-success">+8%</span></td>
                                </tr>
                                <tr>
                                    <td>Central Medical Center</td>
                                    <td>1,945</td>
                                    <td><span class="badge home-badge-warning">+2%</span></td>
                                </tr>
                                <tr>
                                    <td>Northside Clinic</td>
                                    <td>1,230</td>
                                    <td><span class="badge home-badge-danger">-5%</span></td>
                                </tr>
                                <tr>
                                    <td>Community Health Center</td>
                                    <td>876</td>
                                    <td><span class="badge home-badge-success">+15%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Yearly Certificates Chart
        const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
        const yearlyChart = new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Certificates Issued',
                    data: [1024, 1156, 987, 1345, 1567, 1432, 1678, 1567, 1789, 1654, 1432, 1234],
                    backgroundColor: 'rgba(52, 152, 219, 0.7)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [<?php echo  $male["male_gender"] ?>, <?php echo  $female["female_gender"] ?>, 0],
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(155, 89, 182, 0.7)'

                    ],
                    borderColor: [
                        'rgba(52, 152, 219, 1)',
                        'rgba(155, 89, 182, 1)',
                        'rgba(241, 196, 15, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>

</html>