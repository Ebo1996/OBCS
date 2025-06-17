<?php
require 'init.php';

include "../setup/dbconnection.php";


if (!isset($_SESSION["id"])) {
    header:
    "location:../public/login.php";
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
    $cert = $result->fetch_assoc();
    $payment_status = $cert["payment_status"];
    $sql = "SELECT * FROM payments WHERE certificate_id = ?";
    $paystmt = $conn->prepare($sql);
    $paystmt->bind_param("s", $cert["id"]);
    $paystmt->execute();
    $result = $paystmt->get_result();
    $payment = $result->fetch_assoc();



    if ($payment_status == "unpaid" && $result->num_rows == 0) {
        echo '
                <div class="status-card mb-4 payment">
            <div class="status-header text-center">
                <h3 class="mb-0"><i class="bi bi-file-earmark-text-fill status-icon"></i> Birth Certificate Application Status</h3>
            </div>
            <div class="status-body">
                <!-- Approved Status -->
                <div id="approvedStatus">
                    <div class="status-box approved">
                        <i class="bi bi-check-circle-fill status-icon"></i> Your application has been approved!
                    </div>

                    <div class="payment-details">
                        <h5 class="fw-bold text-center mb-3">Payment Information</h5>
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center mb-3 mb-md-0">
                                <div class="payment-amount">ETB 250.00</div>
                                <small class="text-muted">Certificate issuance fee</small>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Application approved</li>
                                    <li class="mb-2"><i class="bi bi-currency-exchange text-primary me-2"></i>Payment pending</li>
                                    <li><i class="bi bi-file-earmark-text text-muted me-2"></i>Certificate not yet issued</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary-custom btn-lg px-4" onclick="showPaymentForm()">
                            <i class="bi bi-credit-card-fill me-2"></i>Proceed to Payment
                        </button>
                    </div>
                </div>';
    } else if ($payment_status == "unpaid" && $result->num_rows > 0) {
        echo 'your payment is pending';
    } else if ($payment_status == "paid") {
        echo '
        
<div class="container">
    <div class="card shadow p-4">
        <h4 class="text-center" style="color: var(--primary-color)">Application Status</h4>
        <div id="statusContainer" class="status-box approved">✅ Your application is approved.</div>
        <div id="certificateButtonContainer" class="text-center mt-4">
            <a href="updatedcertificate.php" class="btn" style="background-color: var(--secondary-color); color: white">View Certificate</a>
        </div>
    </div>
</div>
        ';
    }
} else {
    $sql = "SELECT * FROM applications WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {

        echo '
          <div class="col-md-6 mb-4 appending">
                <div class="status-card h-100">
                    <div class="status-header text-center">
                        <h4 class="mb-0"><i class="bi bi-hourglass-split status-icon"></i> Pending Application</h4>
                    </div>
                    <div class="status-body">
                        <div class="status-box pending">
                            <i class="bi bi-hourglass-top status-icon"></i> Application under review
                        </div>
                        <div class="text-center text-muted mt-3">
                            <p>Your application is being processed. We will notify you by email once a decision is made.</p>
                            <span class="badge bg-warning text-dark badge-status">Pending</span>
                        </div>
                    </div>
                </div>
            </div>';
    } else {

        echo '
          <div class="col-12 ms-5 mt-5">
                <div class="status-card ms-5">
                    <div class="status-header text-center">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-plus status-icon"></i> New Application</h4>
                    </div>
                    <div class="status-body text-center">
                        <div class="status-box pending">
                            <i class="bi bi-exclamation-circle-fill status-icon"></i> No application submitted
                        </div>
                        <p class="text-muted mt-3">You have not applied for a birth certificate yet.</p>
                        <button class="btn btn-primary-custom px-4" href="newstatus.php?page=apply">
                            <i class="bi bi-pencil-square me-2"></i>Start New Application
                        </button>
                    </div>
                </div>
            </div>
        ';
    }
}


if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["submit"])) {
    $target_dir = "uploads/";
    function uploadfile($inputname, $target_dir)
    {
        if (!empty($_FILES[$inputname]["name"])) {
            $filename = basename($_FILES[$inputname]["name"]);
            $filetype = strtolower(pathinfo($_FILES[$inputname]["name"], PATHINFO_EXTENSION));
            $allowedTypes = array("jpg", "jpeg", "png", "pdf");
            if (in_array($filetype, $allowedTypes)) {
                $filepath = $target_dir . time() . "_" . $filename;
                if (move_uploaded_file($_FILES[$inputname]["tmp_name"], $filepath)) {
                    return $filepath;
                } else {
                    echo " error ";
                }
            } else {
                echo "invalid type";
            }
        } else {
            return null;
        }
    }

    $fullname = $_POST["name"];
    $certificate_id = $_POST["certificate_id"];
    $phone = $_POST["phone"];
    $amount = $_POST["amount"];
    $screenshoot_path = uploadfile("payment_screenshoot", $target_dir);
    // $captcha = $_POST["captcha"];

    $sql = "INSERT INTO payments (user_id, full_name,certificate_id,phone,payment_IMG,amount) VALUES (?,?,?,?,?,?)";
    $paymentstmt = $conn->prepare($sql);
    $paymentstmt->bind_param("isssss", $user_id, $fullname, $certificate_id, $phone, $screenshoot_path, $amount);


    if ($paymentstmt->execute()) {
        echo "<script>
         document.getElementById('paymentForm').style.display = 'none';
            document.getElementById('paymentCompleted').style.display = 'block';
            document.getElementById('paymentCompleted').scrollIntoView({
                behavior: 'smooth'
            });
        
        </script>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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

        /* Custom container width (default is 1140px for xl, reduced by 100px) */
        .container {
            max-width: 1040px;
        }

        .appending {
            margin-left: 20rem;
            margin-top: 3rem;

        }

        .payment{
            width: 800px;
            margin: auto
        } 
        .status-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .status-card:hover {
            transform: translateY(-5px);
        }

        .status-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.2rem;
            border-bottom: 4px solid rgba(255, 255, 255, 0.1);
        }

        .status-body {
            padding: 1.8rem;
        }

        .status-box {
            font-size: 1.1rem;
            font-weight: 500;
            padding: 1rem;
            text-align: center;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .approved {
            background-color: rgba(13, 146, 79, 0.1);
            color: var(--secondary-color);
            border-left: 4px solid var(--secondary-color);
        }

        .rejected {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
        }

        .pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #d39e00;
            border-left: 4px solid #ffc107;
        }

        .payment-details {
            background-color: rgba(44, 62, 80, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid var(--primary-color);
        }

        .payment-amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-success-custom {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .btn-outline-custom {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-custom:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .captcha-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 1.2rem 0;
        }

        .captcha-math {
            font-size: 1.2rem;
            font-weight: 600;
            background-color: rgba(44, 62, 80, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: var(--primary-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(44, 62, 80, 0.25);
        }

        .file-upload {
            position: relative;
            overflow: hidden;
        }

        .file-upload-input {
            position: absolute;
            font-size: 100px;
            opacity: 0;
            right: 0;
            top: 0;
        }

        .file-upload-label {
            display: block;
            padding: 0.5rem 1rem;
            background-color: rgba(44, 62, 80, 0.05);
            border: 1px dashed var(--primary-color);
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-label:hover {
            background-color: rgba(44, 62, 80, 0.1);
        }

        .status-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
            vertical-align: middle;
        }

        .divider {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin: 1.5rem 0;
        }

        .badge-status {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 50px;
        }

        .paymentform {
            margin-left: 10rem;
        }
    </style>
</head>

<body>
    <div class="container py-5 me-5 ps-0 ">
        <!-- Main Status Card -->


        <!-- Payment Form (Hidden by default) -->
        <div id="paymentForm" style="display: none;" class="paymentform">
            <div class="divider"></div>
            <h4 class="text-center mb-4" style="color: var(--primary-color)">
                <i class="bi bi-credit-card me-2"></i>Payment Details
            </h4>

            <form action="newstatus.php" method="post" enctype="multipart/form-data" id="paymentSubmissionForm" onsubmit="return validatePayment()">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="fullname" required>
                    </div>
                    <div class="col-md-6">
                        <label for="request_id" class="form-label">certificate ID</label>
                        <input type="text" name="certificate_id" class="form-control" id="request_id">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" id="phone" required>
                    </div>
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Amount (ETB)</label>
                        <input type="number" name="amount" class="form-control" id="amount" value="250" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Payment Screenshot</label>
                        <div class="file-upload">
                            <input type="file" name="payment_screenshoot" id="screenshot" class="file-upload-input" accept=".jpg,.jpeg,.png,.pdf" required>
                            <label for="screenshot" class="file-upload-label" id="fileLabel">
                                <i class="bi bi-cloud-arrow-up me-2"></i>
                                <span>Upload payment confirmation (JPG, PNG or PDF)</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="captcha-container">
                            <div class="captcha-math" id="captcha-question"></div>
                            <input type="text" class="form-control" id="captcha-answer" placeholder="Your answer" required>
                            <button type="button" class="btn btn-sm btn-outline-custom" name="captcha" onclick="generateCaptcha()">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        <div id="captcha-error" class="text-danger small"></div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <button type="submit" name="submit" class="btn btn-success-custom me-md-2 px-4">
                                <i class="bi bi-send-check-fill me-2"></i>Submit Payment
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="hidePaymentForm()">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Payment Success (Hidden by default) -->
        <div id="paymentCompleted" style="display: none;">
            <div class="divider"></div>
            <div class="text-center py-3">
                <div class="status-box approved mb-4">
                    <i class="bi bi-check-circle-fill status-icon"></i> Payment successfully completed!
                </div>
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-envelope-check-fill me-2"></i> A receipt has been sent to your email address.
                </div>
                <button class="btn btn-success-custom btn-lg px-4 mt-3" onclick="viewCertificate()">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>View Certificate
                </button>
            </div>
        </div>
    </div>
    </div>

    <!-- Other Status Examples -->
    <div class="row">
        <!-- Pending Example -->


        <!-- Rejected Example -->
        <!-- <div class="col-md-6 mb-4">
                <div class="status-card h-100">
                    <div class="status-header text-center">
                        <h4 class="mb-0"><i class="bi bi-x-circle-fill status-icon"></i> Rejected Application</h4>
                    </div>
                    <div class="status-body">
                        <div class="status-box rejected">
                            <i class="bi bi-slash-circle-fill status-icon"></i> Application not approved
                        </div>
                        <div class="text-center text-muted mt-3">
                            <p>Your application didn't meet the requirements. Please contact support for more information.</p>
                            <span class="badge bg-danger badge-status">Rejected</span>
                        </div>
                    </div>
                </div>
            </div> -->

        <!-- Not Applied Example -->

    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Generate random numbers for CAPTCHA
        let num1, num2, correctAnswer;

        function generateCaptcha() {
            num1 = Math.floor(Math.random() * 10) + 1;
            num2 = Math.floor(Math.random() * 10) + 1;
            correctAnswer = num1 + num2;

            document.getElementById('captcha-question').textContent = `${num1} + ${num2} = ?`;
            document.getElementById('captcha-answer').value = '';
            document.getElementById('captcha-error').textContent = '';
        }

        function validateCaptcha() {
            const userAnswer = parseInt(document.getElementById('captcha-answer').value);
            const errorElement = document.getElementById('captcha-error');

            if (isNaN(userAnswer)) {
                errorElement.textContent = 'Please enter a valid number';
                return false;
            }

            if (userAnswer !== correctAnswer) {
                errorElement.textContent = 'Incorrect answer. Please try again.';
                generateCaptcha();
                return false;
            }

            return true;
        }

        // Show payment form
        function showPaymentForm() {
            document.getElementById('paymentForm').style.display = 'block';
            document.getElementById('paymentForm').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Hide payment form
        function hidePaymentForm() {
            document.getElementById('paymentForm').style.display = 'none';
        }

        // Validate payment submission
        function validatePayment() {
            if (!validateCaptcha()) {
                return false;
            }

            // Show payment success


            // Prevent actual form submission for demo
        }

        // Mock certificate viewing
        function viewCertificate() {
            alert('In a real implementation, this would redirect to the certificate download page.');
        }

        // Update file upload label
        document.getElementById('screenshot').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Upload payment confirmation (JPG, PNG or PDF)';
            document.getElementById('fileLabel').innerHTML =
                `<i class="bi bi-cloud-arrow-up me-2"></i><span>${fileName}</span>`;
        });

        // Generate initial CAPTCHA when page loads
        window.onload = generateCaptcha;
    </script>
</body>

</html>