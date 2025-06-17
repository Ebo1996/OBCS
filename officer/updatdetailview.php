<?php
session_start();
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../setup/dbconnection.php';

// Initialize all variables at the top
$doc = [
    'applicant_id' => 'photo is not uploaded',
    'mother_id' => '',
    'father_id' => '',
    'birth_record' => ''
];

$birth_record = [];
$hospital = [];
$app_id = '';

if (isset($_GET["app_id"]) && !empty($_GET["app_id"])) {
    $app_id = $_GET["app_id"];

    // $sql = "SELECT father_id, mother_id, applicant_id, birth_record FROM applications WHERE id = ?";
    $sql = "SELECT  * FROM applications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Application is not found");
    } else {
        $application = $result->fetch_assoc();
    }
}

if (isset($_POST["certificate_submit"])) {
    $certificateFound = false;
    $certificate_id = $_POST["certificate_id"];
    $sql = "SELECT * FROM birth_records WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $certificate_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $birth_record = $result->fetch_assoc();
            $certificateFound = true;
            $hospital_id = isset($birth_record["hospital_id"]) ? $birth_record["hospital_id"] : null;

            if (!empty($hospital_id)) {
                $sql = "SELECT * FROM hospitals WHERE hospital_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $hospital_id);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $hospital = $result->fetch_assoc();
                    }
                }
            }
        }
    }
}

if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message_of_rejection"];
    $app_id = $_POST["app_id"];
    $sql = "SELECT * FROM applications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $email = $application["email"];
    $full_name = $application["first_name"] . " " . $application["middle_name"] . " " . $application["last_name"];
    $title = "rejected application";
    $sql = "INSERT INTO messages (user_id, title, body) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $application["user_id"], $title,  $message);

    if ($stmt->execute()) {
        $sql = "DELETE FROM applications WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $app_id);
        if ($stmt->execute()) {

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'bonsadaba8@gmail.com';
                $mail->Password = 'nfcg vsoa oyhm etyv';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('bonsadaba8@gmail.com', 'ifa bula certificate office');
                $mail->addAddress($email, $full_name);
                $mail->Subject = 'your application has been rejected';
                $mail->Body = "hello $full_name, your application has been rejected. reason: $message";
                $mail->send();
                echo "application rejected";
            } catch (Exception $e) {
                echo "application regected but email failed: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Certificate Management - View Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        /* :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        } */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .photo-container {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .main-photo {
            height: 300px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
        }

        /* Modern ID Card Design */
        .modern-id-card {
            width: 100%;
            max-width: 600px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 1px solid #d1d9e6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            margin-bottom: 30px;
        }

        .id-card-header {
            background: linear-gradient(to right, #3498db, #2c3e50);
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        .id-card-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .id-card-body {
            display: flex;
            padding: 20px;
            position: relative;
        }

        .id-photo-section {
            width: 150px;
            margin-right: 20px;
            position: relative;
        }

        .id-main-photo {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .id-details-section {
            flex: 1;
        }

        .id-detail-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .id-detail-label {
            width: 120px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .id-detail-value {
            flex: 1;
            padding: 6px 10px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 4px;
            font-size: 0.95rem;
            color: #34495e;
            border: 1px solid #e0e4e8;
        }

        .id-card-footer {
            background: #f8f9fa;
            padding: 12px 20px;
            text-align: center;
            border-top: 1px solid #e0e4e8;
            font-size: 0.8rem;
            color: #7f8c8d;
        }

        .id-card-qr {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
            background: white;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .id-card-qr img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .id-card-watermark {
            position: absolute;
            opacity: 0.05;
            font-size: 120px;
            font-weight: bold;
            color: #2c3e50;
            transform: rotate(-30deg);
            pointer-events: none;
            z-index: 0;
        }

        /* Certificate Styles */
        .certificate-modern {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border: 1px solid #e1e5ee;
            position: relative;
            overflow: hidden;
        }

        .certificate-modern::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
        }

        .certificate-header-modern {
            text-align: center;
            margin-bottom: 30px;
        }

        .certificate-header-modern h5 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .certificate-header-modern h6 {
            color: #4a5568;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .certificate-header-modern h4 {
            color: #3498db;
            font-size: 24px;
            margin: 20px 0;
            position: relative;
        }

        .certificate-header-modern h4::after {
            content: "";
            display: block;
            width: 100px;
            height: 3px;
            background: #3498db;
            margin: 10px auto;
        }

        .certificate-content-modern {
            display: flex;
            gap: 30px;
        }

        .certificate-photo-modern {
            width: 150px;
            height: 180px;
            border: 3px solid #f1f5f9;
            border-radius: 5px;
            object-fit: cover;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .certificate-details-modern {
            flex: 1;
        }

        .certificate-table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .certificate-table-modern th {
            text-align: left;
            padding: 10px 15px;
            background-color: #f8fafc;
            color: #4a5568;
            width: 35%;
            border: 1px solid #e2e8f0;
        }

        .certificate-table-modern td {
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            color: #2d3748;
        }

        .certificate-footer-modern {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e0;
        }

        .signature-box-modern {
            text-align: center;
            flex: 1;
        }

        .thumbnail {
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 8px;
            overflow: hidden;
        }

        .thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .search-tabs .nav-link {
            font-weight: 500;
            color: #495057;
            border: none;
        }

        .search-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: transparent;
            border-bottom: 3px solid var(--primary-color);
        }

        .search-content {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-approve {
            background-color: var(--success-color);
            color: white;
            font-weight: 500;
        }

        .btn-reject {
            background-color: var(--danger-color);
            color: white;
            font-weight: 500;
        }

        .application-details {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .detail-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: 600;
            color: var(--secondary-color);
            width: 200px;
        }

        .detail-value {
            flex: 1;
            color: #555;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .animate-show {
            animation: fadeIn 0.5s ease-out;
        }

        .regectpopuphidden {
            display: none;

        }

        .containerregect {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: rgb(80, 81, 82);
            height: 500px;
            width: 600px;
        }

        .regectpopupvisible {
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            background-color: rgba(1, 0, 0, 0.5)
        }

        .regect_reason {
            display: flex;
            gap: 1rem;
            background-color: #3498db;
            padding: 1rem;
            border-radius: 10px;

        }

        .form_container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            margin-top: 2rem;




        }

        .closepopup {
            background-color: red;
            color: black;
            padding: 10px;
            border-radius: 10px;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Left Column - Application Details -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Application Details</h5>
                        <span class="status-badge status-pending">Pending Review</span>
                    </div>
                    <div class="card-body dflex">
                        <div class="detail-row">
                            <div class="detail-label">Application ID:
                            </div>
                            <div class="detail-value">
                                <?php echo $application["id"] ?>
                            </div>

                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date Submitted:</div>
                            <div class="detail-value">
                                <?php echo $application["submission_date"] ?>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Applicant Name:</div>
                            <div class="detail-value">
                                <?php echo $application["first_name"] . " " . $application["middle_name"] . " " . $application["last_name"] ?>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Contact Email:</div>
                            <div class="detail-value">
                                <?php echo $application["email"] ?>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Contact Phone:</div>
                            <div class="detail-value">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Document Photos</h5>
                    </div>
                    <div class="card-body">
                        <div class="main-photo mb-3" id="mainPhotoDisplay">
                            <p class="text-muted">Click a thumbnail to display photo here</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($application['applicant_id']) ?>')">
                                    <img src="<?= htmlspecialchars($application['applicant_id']) ?>" alt="Applicant ID" class="img-thumbnail w-100">
                                    <p class="text-center mt-2 small">Applicant ID</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($application['mother_id']) ?>')">
                                    <img src="<?= htmlspecialchars($application['mother_id']) ?>" alt="Mother ID" class="img-thumbnail w-100">
                                    <p class="text-center mt-2 small">Mother ID</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($application['father_id']) ?>')">
                                    <img src="<?php echo htmlspecialchars($application['father_id']) ?>" alt="Father ID" class="img-thumbnail w-100">
                                    <p class="text-center mt-2 small">Father ID</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($application['birth_record']) ?>')">
                                    <img src="<?= htmlspecialchars($application['birth_record']) ?>" alt="Birth Record" class="img-thumbnail w-100">
                                    <p class="text-center mt-2 small">Birth Record</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="sidebar1.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                    <div>
                        <button class="btn btn-approve me-2" onclick="approved(<?= $application['id'] ?>)">
                            <i class="bi bi-check-circle"></i> Approve Application
                        </button>
                        <button class="btn btn-reject" onclick="regectapplication()">
                            <i class="bi bi-x-circle"></i> Reject Application
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column - Search Section -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Record Verification</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs search-tabs mb-3" id="searchTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="id-tab" data-bs-toggle="tab" data-bs-target="#id-search" type="button" role="tab">ID Search</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="certificate-tab" data-bs-toggle="tab" data-bs-target="#certificate-search" type="button" role="tab">Certificate Search</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="searchTabContent">
                            <div class="tab-pane fade show active" id="id-search" role="tabpanel">
                                <form id="idSearchForm" class="search-content">
                                    <div class="mb-3">
                                        <label for="idNumber" class="form-label">ID Number</label>
                                        <input type="text" class="form-control" name="kebele_id" id="idNumber" required>
                                    </div>
                                    <button type="submit" name="id_submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Search ID
                                    </button>
                                </form>
                                <div id="idresult" class="mt-4 animate-show"></div>
                            </div>

                            <div class="tab-pane fade" id="certificate-search" role="tabpanel">
                                <form id="certificateSearchForm" class="search-content">
                                    <div class="mb-3">
                                        <label for="certificateNumber" class="form-label">Certificate Number</label>
                                        <input type="text" name="certificate_id" class="form-control" id="certificateNumber" required>
                                    </div>
                                    <button type="submit" name="certificate_submit" class="btn btn-success">
                                        <i class="bi bi-search"></i> Search Certificate
                                    </button>
                                </form>
                                <div id="certificateResult" class="mt-4 animate-show"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Results Section -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Verification Results</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Search for ID or Certificate records to verify application details.
                        </div>
                        <div id="verificationResults"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="regectpopup" class="regectpopuphidden">
        <div class="containerregect">
            <div class="regect_reason">
                <button class="closepopup" onclick="document.getElementById('regectpopup').style.display='none'">close</button>
                <h1>write the reason for regection </h1>

            </div>

            <form id="regectreason" class="form_container" method="POST" action="updatdetailview.php">
                <input type="hidden" value="<?php echo $application["id"] ?>" name="app_id" id="app_id">
                <textarea name="message_of_rejection" id="message_of_rejection" cols="50" rows="10" placeholder="write reason here"></textarea>
                <div id="error_message" class="error_message"></div>
                <button type="submit" name="submit">send</button>
            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function displayPhoto(photoSrc) {
            const mainPhotoDisplay = document.getElementById('mainPhotoDisplay');
            if (photoSrc && photoSrc !== 'photo is not uploaded') {
                mainPhotoDisplay.innerHTML = `<img src="${photoSrc}" alt="Displayed Photo" class="img-fluid" style="max-height: 100%;">`;
            } else {
                mainPhotoDisplay.innerHTML = '<p class="text-muted">Photo not available</p>';
            }
        }

        function approved(appId) {
            if (confirm("Are you sure you want to approve this application?")) {
                fetch('approved.php', {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'app_id=' + encodeURIComponent(appId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Application approved successfully!");
                            window.location.href = "sidebar.php?page=application";
                        } else {
                            alert("Error: " + (data.message || "Failed to approve application"));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("An error occurred while approving the application");
                    });
            }
        }

        function regectapplication() {
            const regectdiv = document.getElementById('regectpopup');
            regectdiv.classList.remove('regectpopuphidden');
            regectdiv.classList.add('regectpopupvisible');
        }

        function closepopup() {
            const regectdiv = document.getElementById('regectpopup');
            regectdiv.classList.remove(regectpopupvisible);
            regectdiv.classList.add('regectpopuphidden');

        }

        // Initialize photo display
        displayPhoto('<?= htmlspecialchars($doc['applicant_id']) ?>');

        // ID Search
        document.getElementById('idSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const resultdiv = document.getElementById('idresult');
            const certdiv = document.getElementById('certificateResult');
            certdiv.style.display = "none";
            resultdiv.style.display = "block";
            resultdiv.innerHTML = `<div class="text-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Searching...</p>
    </div>`;

            const idvalue = document.getElementById('idNumber').value;

            fetch('search_ID.php', {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'kebele_id=' + encodeURIComponent(idvalue)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultdiv.innerHTML = `
    <div class="modern-id-card animate-show">
        <div class="id-card-header">
            <h4>NATIONAL ID CARD</h4>
        </div>
        <div class="id-card-body">
            <div class="id-photo-section">
                <img src="../kebeledashboard/${data.kebele_id.photo_path || 'https://via.placeholder.com/150'}"
                    alt="ID Photo" class="id-main-photo">
            </div>
            <div class="id-details-section">
                <div class="id-detail-row">
                    <div class="id-detail-label">ID Number:</div>
                    <div class="id-detail-value">${data.kebele_id.kebele_id_number}</div>
                </div>
                <div class="id-detail-row">
                    <div class="id-detail-label">Full Name:</div>
                    <div class="id-detail-value">
                        ${data.kebele_id.first_name} ${data.kebele_id.middle_name || ''} ${data.kebele_id.last_name}
                    </div>
                </div>
                <div class="id-detail-row">
                    <div class="id-detail-label">Date of Birth:</div>
                    <div class="id-detail-value">${data.kebele_id.date_of_birth || 'N/A'}</div>
                </div>
                <div class="id-detail-row">
                    <div class="id-detail-label">Gender:</div>
                    <div class="id-detail-value">${data.kebele_id.gender || 'N/A'}</div>
                </div>
                <div class="id-detail-row">
                    <div class="id-detail-label">Kebele:</div>
                    <div class="id-detail-value">${data.kebele_id.kebele_name || 'N/A'}</div>
                </div>
            </div>
            <div class="id-card-qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${data.kebele_id.kebele_id_number}"
                    alt="QR Code">
            </div>
        </div>
        <div class="id-card-footer">
            Federal Democratic Republic of Ethiopia • Ministry of Interior
        </div>
    </div>`;
                    } else {
                        resultdiv.innerHTML = `<div class="alert alert-danger">${data.message || 'ID not found'}</div>`;
                    }
                })
                .catch(err => {
                    resultdiv.innerHTML = `<div class="alert alert-danger">Error fetching data</div>`;
                    console.error(err);
                });
        });

        // Certificate Search
        document.getElementById('certificateSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const certdiv = document.getElementById('certificateResult');
            const idresult = document.getElementById('idresult');
            idresult.style.display = "none";
            certdiv.style.display = "block";
            certdiv.innerHTML = `<div class="text-center py-4">
             <div class="spinner-border text-success" role="status"></div>
        <p class="mt-2">Searching...</p>
    </div>`;

            const certId = document.getElementById('certificateNumber').value;

            fetch('search_certificate.php', {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'certificate_id=' + encodeURIComponent(certId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        certdiv.innerHTML = `
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Certificate Verification Result</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-sm">
                        <tr>
                            <th>Certificate ID:</th>
                            <td>${data.birth_record.record_id}</td>
                        </tr>
                        <tr>
                            <th>Child Name:</th>
                            <td>${data.birth_record.child_name}</td>
                        </tr>
                        <tr>
                            <th>Father's Name:</th>
                            <td>${data.birth_record.father_name}</td>
                        </tr>
                        <tr>
                            <th>Mother's Name:</th>
                            <td>${data.birth_record.mother_name}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td>${data.birth_record.dob}</td>
                        </tr>
                        <tr>
                            <th>Hospital:</th>
                            <td>${data.hospital.name}</td>
                        </tr>
                    </table>
                    <div class="text-center mt-2">
                        <button class="btn btn-sm btn-outline-success">
                            <i class="bi bi-check-circle"></i> Verify Match
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${data.birth_record.record_id}"
                            alt="QR Code" class="img-fluid">
                        <p class="small mt-2">Certificate QR Code</p>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
                    } else {
                        certdiv.innerHTML = `<div class="alert alert-danger">Certificate not found</div>`;
                    }
                })
                .catch(err => {
                    certdiv.innerHTML = `<div class="alert alert-danger">Error fetching data</div>`;
                    console.error(err);
                });
        });

        // Initialize Bootstrap tabs
        const searchTab = new bootstrap.Tab(document.getElementById('id-tab'));
    </script>
</body>

</html>