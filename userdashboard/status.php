<?php
require 'init.php';

include "../setup/dbconnection.php";


if (!isset($_SESSION["id"])) {
    header:"location:../public/login.php";
} else {
    $user_id = $_SESSION["id"];
}

// First, check the certificates table
$sql = "SELECT * FROM certificates WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Certificate exists
    $cert = $result->fetch_assoc();

    echo '
<div class="container">
    <div class="card shadow p-4">
        <h4 class="text-center" style="color: var(--primary-color)">Application Status</h4>
        <div id="statusContainer" class="status-box approved">✅ Your application is approved.</div>
        <div id="certificateButtonContainer" class="text-center mt-4">
            <a href="updatedcertificate.php" class="btn" style="background-color: var(--secondary-color); color: white">View Certificate</a>
        </div>
    </div>
</div>';
} else {
    // No certificate, check application status
    $sql = "SELECT * FROM applications WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $app = $result->fetch_assoc();
        $status = $app["status"];
        echo $status;

        if ($status == "Pending") {
            echo '
               <div class="container me-5">
                  <div class="card shadow p-4">
                      <h4 class="text-center" style="color: var(--primary-color)">Application Status</h4>
                      <div id="statusContainer" class="status-box pending"> ⏳ Your application is pending. </div>
                      <div id="certificateButtonContainer" class="text-center mt-3">we notify you by your email</div>
                  </div>
               </div>
            ';
        } elseif ($status == "regect") {
            echo '
               <div class="container">
                  <div class="card shadow p-4">
                      <h4 class="text-center" style="color: var(--primary-color)">Application Status</h4>
                      <div id="statusContainer" class="status-box rejected"> ❌ Your application is rejected. </div>
                      <div id="certificateButtonContainer" class="text-center mt-3"></div>
                  </div>
               </div>
            ';
        } else {
            echo "ℹ️ Application status: " . $status;
        }
    } else {
        echo '
               <div class="container">
                  <div class="card shadow p-4">
                      <h4 class="text-center" style="color: var(--primary-color)">Application Status</h4>
                      <div id="statusContainer" class="status-box pending">  you are not apply for birth certificate yet </div>
                      <div id="certificateButtonContainer" class="text-center mt-3"><a href="user.php?page=apply" class="btn" style="background-color: var(--secondary-color); color: white">apply now</a></div>
                  </div>
               </div>
            ';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Certificate Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #0d924f;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
        }
        
        .container {
            max-width: 600px;
            margin-top: 20px;
        }

        .status-box {
            font-size: 1.2rem;
            font-weight: bold;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin: 10px 0;
        }

        .approved {
            background-color: #d4edda;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
        }

        .rejected {
            background-color: #f8d7da;
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }

        .pending {
            background-color: #fff3cd;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        body {
            background-color: var(--light-bg);
        }
        
        .card {
            border: none;
            border-radius: 10px;
        }
        
        .btn {
            padding: 8px 20px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>