<?php
require "../userdashboard/init.php";
include "../setup/dbconnection.php";
$user_id = $_SESSION["id"];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql = "SELECT * FROM applications WHERE id = ?";
$appstmt = $conn->prepare($sql);
$appstmt->bind_param("i", $user_id);
$appstmt->execute();
$result = $appstmt->get_result();
$totalapp = $result->num_rows;
$sql = "SELECT * FROM certificates WHERE id = ?";
$certstmt = $conn->prepare($sql);
$certstmt->bind_param("i", $user_id);
$certstmt->execute();
$result = $certstmt->get_result();
$totalcert = $result->num_rows;

$totalrequest = $totalapp + $totalcert;

$sql = "SELECT * FROM announcements ORDER BY posted_at DESC LIMIT 3";
$annstmt = $conn->prepare($sql);
$annstmt->execute();
$result = $annstmt->get_result();
$count = 3;




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Birth Certificate System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #0d924f;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', sans-serif;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .dashboard-container {
            padding: 2rem;
            width: calc(100% - 250px);
            margin-left: auto;
            transition: all 0.3s;
        }

        .welcome-box {
            background: linear-gradient(135deg, var(--primary-color), #1a252f);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .card-requests {
            border-top: 4px solid var(--primary-color);
        }

        .card-approved {
            border-top: 4px solid var(--secondary-color);
        }

        .card-pending {
            border-top: 4px solid #f39c12;
        }

        .announcement-box {
            background-color: white;
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .announcement-box:hover {
            transform: translateX(5px);
        }

        .section-title {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 1.5rem;
        }

        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: var(--secondary-color);
        }

        @media (max-width: 992px) {
            .dashboard-container {
                width: 100%;
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="dashboard-container">
            <div class="welcome-box text-center">
                <h2>Welcome, <?php echo htmlspecialchars($user["first_name"] . " " . $user["last_name"]); ?>!</h2>
                <p class="lead mb-0">Manage your birth certificate requests and view updates here.</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stats-card shadow card-requests">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt text-primary"></i>
                            <h5 class="card-title mt-2">Total Requests</h5>
                            <p class="card-text fs-3 fw-bold"><?php echo $totalrequest ?></p>
                            <small class="text-muted">View all requests</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card shadow card-approved">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle text-success"></i>
                            <h5 class="card-title mt-2">Approved</h5>
                            <p class="card-text fs-3 fw-bold"><?php echo $totalcert ?></p>
                            <small class="text-muted">View approved certificates</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card shadow card-pending">
                        <div class="card-body text-center">
                            <i class="fas fa-hourglass-half text-warning"></i>
                            <h5 class="card-title mt-2">Pending</h5>
                            <p class="card-text fs-3 fw-bold"><?php echo $totalapp ?></p>
                            <small class="text-muted">Track pending requests</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h4 class="section-title">Latest Announcements</h4>
                <?php while ($result->num_rows > 0 && $count > 0) {
                    $announcement = $result->fetch_assoc();
                    echo '
                    <div class="announcement-box mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-bullhorn me-2 text-primary"></i>
                        <h6 class="mb-0">'.$announcement["title"].'</h6>
                    </div>
                    <p class="mb-0">'.$announcement["body"].'</p>
                </div>
                   ';
                   $count --;
                }
                ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to stats cards on page load
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Make announcement boxes clickable (example functionality)
            const announcementBoxes = document.querySelectorAll('.announcement-box');
            announcementBoxes.forEach(box => {
                box.style.cursor = 'pointer';
                box.addEventListener('click', function() {
                    // You can add functionality here, like showing a modal
                    console.log('Announcement clicked:', this.querySelector('h6').textContent);
                });
            });
        });
    </script>
</body>

</html>