<?php
require 'init.php';
include "../setup/dbconnection.php";

$email = $_SESSION["email"];
$user_id = $_SESSION["id"];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $oldpassword = $_POST["oldpassword"];
    $newpassword = $_POST["newpassword"];
    $confirmpassword = $_POST["confirmpassword"];
    $phone = $_POST["phone"];

    if (!password_verify($oldpassword, $user["password"])) {
        $error = "Incorrect current password";
    } else if (!empty($newpassword) && $newpassword != $confirmpassword) {
        $error = "New passwords don't match";
    } else {
        if (!empty($newpassword)) {
            $hashedpassword = password_hash($newpassword, PASSWORD_DEFAULT);
        } else {
            $hashedpassword = $user["password"]; // keep the current password
        }

        $sql = "UPDATE users SET password = ?, phone = ? WHERE id= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $hashedpassword, $phone, $user_id);
        if ($stmt->execute()) {
            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>";



            $user["phone"] = $phone;
        } else {
            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        });
    </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-gray: #f8f9fa;
        } */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #0d924f;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .profile-container {
            margin-left: 10rem;
            width: 110rem;
            /* Match sidebar width */
            padding: 2rem;
            transition: margin 0.3s ease;
        }

        .profile-card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 5px solid white;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 1rem;
        }

        .profile-body {
            padding: 2rem;
        }

        .info-card {
            background-color: var(--light-gray);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
        }

        .btn-edit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-edit:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .edit-form {
            background-color: var(--light-gray);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            animation: fadeIn 0.3s ease;
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

        .alert {
            border-radius: 10px;
        }

        @media (max-width: 992px) {
            .profile-container {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar would be included here -->

    <div class="profile-container ms-5">
        <div class="profile-card">
            <div class="profile-header">
                <img src="../assets/uploads/<?php echo htmlspecialchars($user["profile_image"] ?? 'profile.png') ?>"
                    alt="Profile Image"
                    class="profile-avatar">
                <h3><?php echo htmlspecialchars($user["first_name"] . " " . $user["last_name"]) ?></h3>
                <p class="mb-0"><i class="fas fa-user-shield me-2"></i><?php echo ucfirst($user["role"]) ?></p>
            </div>

            <div class="profile-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error ?></div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success ?></div>
                <?php endif; ?>

                <div class="info-card">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        <?php echo htmlspecialchars($user["email"]) ?>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">
                        <i class="fas fa-phone me-2 text-muted"></i>
                        <?php echo htmlspecialchars($user["phone"] ?? 'Not provided') ?>
                    </div>
                </div>

                <button type="button" class="btn btn-edit w-100" onclick="toggleEditForm()">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </button>

                <div id="editForm" class="edit-form d-none">
                    <form action="editprofile.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control"
                                    value="<?php echo htmlspecialchars($user["phone"] ?? '') ?>"
                                    placeholder="Enter your phone number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="oldpassword" class="form-control"
                                    placeholder="Enter current password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="newpassword" class="form-control"
                                    placeholder="Enter new password">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="confirmpassword" class="form-control"
                                    placeholder="Confirm new password">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleEditForm()">
                                Cancel
                            </button>
                        </div>
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
                    your profile is changed successfully
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
        function toggleEditForm() {
            const form = document.getElementById('editForm');
            form.classList.toggle('d-none');

            // Scroll to form if opening
            if (!form.classList.contains('d-none')) {
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>

</html>