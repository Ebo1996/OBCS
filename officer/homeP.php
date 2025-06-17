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
$stmt-> execute();
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
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }

        .sidebar .nav-link:hover {
            color: white;
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .card-icon {
            font-size: 2rem;
            opacity: 0.7;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">


            <!-- Main Content -->  
            <div class="col-md-8 p-4 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4 mr-4">
                    <h2>Admin Dashboard</h2>
                    <div class="d-flex align-items-center">
                        <span class="me-3">Welcome, <?php echo $addmin["first_name"]. " ". $addmin["last_name"] ?></span>
                        <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Profile">
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card shadow-sm border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Total Certificates</h6>
                                        <h3><?php echo $total["totalcertificate"]?></h3>
                                        <small class="text-success"><i class="fas fa-arrow-up"></i> 5.2% from last month</small>
                                    </div>
                                    <div class="card-icon text-primary">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card shadow-sm border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">This Month</h6>
                                        <h3>1,243</h3>
                                        <small class="text-success"><i class="fas fa-arrow-up"></i> 12.7% from last month</small>
                                    </div>
                                    <div class="card-icon text-success">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card shadow-sm border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Pending Requests</h6>
                                        <h3><?php echo $pending["Pending"]?></h3>
                                        <small class="text-danger"><i class="fas fa-arrow-down"></i> 3.4% from last month</small>
                                    </div>
                                    <div class="card-icon text-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card shadow-sm border-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Rejected</h6>
                                        <h3>23</h3>
                                        <small class="text-danger"><i class="fas fa-arrow-up"></i> 1.2% from last month</small>
                                    </div>
                                    <div class="card-icon text-danger">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-8 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Certificates Issued (Last 12 Months)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="yearlyChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Gender Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="genderChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Top Locations -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recent Activity</h5>
                                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>New certificate issued</strong>
                                                <p class="mb-0 text-muted">Certificate #BC-2023-12456</p>
                                            </div>
                                            <small class="text-muted">2 mins ago</small>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>Registration approved</strong>
                                                <p class="mb-0 text-muted">For Baby Smith (ID: 7890)</p>
                                            </div>
                                            <small class="text-muted">25 mins ago</small>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>Certificate printed</strong>
                                                <p class="mb-0 text-muted">Certificate #BC-2023-12455</p>
                                            </div>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>New user registered</strong>
                                                <p class="mb-0 text-muted">Dr. Johnson (Hospital Admin)</p>
                                            </div>
                                            <small class="text-muted">3 hours ago</small>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>Request rejected</strong>
                                                <p class="mb-0 text-muted">Incomplete documentation</p>
                                            </div>
                                            <small class="text-muted">5 hours ago</small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Top Registration Locations</h5>
                            </div>
                            <div class="card-body">
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
                                                <td><span class="badge bg-success">+12%</span></td>
                                            </tr>
                                            <tr>
                                                <td>Women & Children Hospital</td>
                                                <td>2,789</td>
                                                <td><span class="badge bg-success">+8%</span></td>
                                            </tr>
                                            <tr>
                                                <td>Central Medical Center</td>
                                                <td>1,945</td>
                                                <td><span class="badge bg-warning text-dark">+2%</span></td>
                                            </tr>
                                            <tr>
                                                <td>Northside Clinic</td>
                                                <td>1,230</td>
                                                <td><span class="badge bg-danger">-5%</span></td>
                                            </tr>
                                            <tr>
                                                <td>Community Health Center</td>
                                                <td>876</td>
                                                <td><span class="badge bg-success">+15%</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    data: [<?php echo  $male["male_gender"]?>, <?php echo  $female["female_gender"]?>,0],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
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
                }
            }
        });
    </script>
</body>

</html>