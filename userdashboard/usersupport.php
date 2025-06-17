<?php
require "init.php";
// Connect to the database
include "../setup/dbconnection.php";
$user_id = $_SESSION["id"];

// Handle Officer Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['officer_submit'])) {

        $subject = $_POST['officer_subjet'];
        $email = $_POST['officer_email'];
        $feedback = $_POST['officer_feedback'];
        $sql = "INSERT INTO applications_support (user_id,email,subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $email, $subject,  $feedback);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['support_success'] = true;
            header("Location: user.php");
            exit();
        }else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                });
            </script>";
        }
        

        $stmt->close();
    }

    // Handle Admin Form Submission
    if (isset($_POST['admin_submit'])) {
        $subject = $_POST['admin_subject'];
        $email = $_POST['admin_email'];
        $feedback = $_POST['admin_feedback'];
        $sql = "INSERT INTO account_support (user_id, email,subject, message) VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $email, $subject,  $feedback);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['support_success'] = true;
            header("Location: user.php");
            exit();
        } else {
            echo "Error submitting admin support.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .support-card {
            max-width: 600px;
            margin: 3rem auto;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-bottom: none;
        }

        .nav-pills .nav-link {
            color: var(--primary-color);
            font-weight: 600;
            border-radius: 0;
            padding: 1rem;
        }

        .nav-pills .nav-link.active {
            background-color: white;
            color: var(--secondary-color);
            border-bottom: 3px solid var(--secondary-color);
        }

        .card-body {
            padding: 2rem;
        }

        .form-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .btn-submit {
            background-color: var(--secondary-color);
            border: none;
            padding: 0.5rem 1.75rem;
            font-weight: 600;
        }

        .btn-submit:hover {
            background-color: #0b7a41;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 146, 79, 0.25);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="card support-card">
            <div class="card-header">
                <h3 class="mb-1">User Support Portal</h3>
                <p class="mb-0">Select your role and submit your request</p>
            </div>

            <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                    <button class="nav-link active" id="officerBtn">To OFFICER</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="adminBtn">To ADMIN</button>
                </li>
            </ul>

            <div class="card-body">
                <!-- Officer Form (default visible) -->
                <div id="officerForm">
                    <h4 class="form-title">Officer Support Request</h4>
                    <form action="usersupport.php" method="post">
                        <div class="mb-3">
                            <label for="officerSubject" class="form-label">Subject</label>
                            <input type="text" name="officer_subjet" class="form-control" id="officerSubject" placeholder="Enter subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="officerEmail" class="form-label">Email</label>
                            <input type="email" name="officer_email" class="form-control" id="officerEmail" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="officerFeedback" class="form-label">Feedback</label>
                            <textarea class="form-control" name="officer_feedback" id="officerFeedback" rows="4" placeholder="Describe your issue or feedback" required></textarea>
                        </div>
                        <button type="submit" name="officer_submit" class="btn btn-submit">Submit to Officer</button>
                    </form>
                </div>

                <!-- Admin Form (hidden by default) -->
                <div id="adminForm" class="d-none">
                    <h4 class="form-title">Admin Support Request</h4>
                    <form action="usersupport.php" method="post">
                        <div class="mb-3">
                            <label for="adminSubject" class="form-label">Subject</label>
                            <input type="text" name="admin_subject" class="form-control" id="adminSubject" placeholder="Enter subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">Email</label>
                            <input type="email" name="admin_email" class="form-control" id="adminEmail" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="adminFeedback" class="form-label">Feedback</label>
                            <textarea class="form-control" name="admin_feedback" id="adminFeedback" rows="4" placeholder="Describe your issue or feedback" required></textarea>
                        </div>
                        <button type="submit" name="admin_submit" class="btn btn-submit">Submit to Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-success">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Your support request was submitted successfully!
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Something went wrong. Please try again.
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const officerBtn = document.getElementById('officerBtn');
            const adminBtn = document.getElementById('adminBtn');
            const officerForm = document.getElementById('officerForm');
            const adminForm = document.getElementById('adminForm');

            officerBtn.addEventListener('click', function() {
                officerBtn.classList.add('active');
                adminBtn.classList.remove('active');
                officerForm.classList.remove('d-none');
                adminForm.classList.add('d-none');
            });

            adminBtn.addEventListener('click', function() {
                adminBtn.classList.add('active');
                officerBtn.classList.remove('active');
                adminForm.classList.remove('d-none');
                officerForm.classList.add('d-none');
            });
        });
    </script>
</body>

</html>