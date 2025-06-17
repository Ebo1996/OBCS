<?php
include "../setup/dbconnection.php";
if(isset($_GET["app_id"])){
    $user_id = $_GET["app_id"];
}
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows){
    $user = $result->fetch_assoc();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .detail-item {
            border-bottom: 1px solid #eee;
            padding: 12px 0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .badge-status {
            font-size: 0.8rem;
            padding: 5px 10px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Back Button -->
                <a href="user_management.html" class="btn btn-outline-secondary mb-4">
                    <i class="fas fa-arrow-left me-2"></i>Back to Users
                </a>

                <!-- Profile Card -->
                <div class="profile-card">
                    <!-- Header Section -->
                    <div class="profile-header text-center">
                        <img src="https://via.placeholder.com/150" alt="Profile Photo" class="profile-img mb-3">
                        <h3 id="userName"><?= $user["first_name"]." ". $user["last_name"]?></h3>
                        <div>
                            <span class="badge bg-success badge-status" id="userStatus">Active</span>
                            <span class="badge bg-primary badge-status" id="userRole"><?= $user["role"]?></span>
                        </div>
                    </div>

                    <!-- Body Section -->
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column - Basic Info -->
                            <div class="col-md-6">
                                <h5 class="card-title mb-4">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>Basic Information
                                </h5>

                                <div class="detail-item">
                                    <small class="text-muted">Full Name</small>
                                    <p class="mb-0 fw-bold" id="fullName"><?= $user["first_name"]." ". $user["last_name"]?></p>
                                </div>

                                <div class="detail-item">
                                    <small class="text-muted">Email Address</small>
                                    <p class="mb-0 fw-bold" id="userEmail"><?= $user["email"] ?></p>
                                </div>

                                <div class="detail-item">
                                    <small class="text-muted">Phone Number</small>
                                    <p class="mb-0 fw-bold" id="userPhone"><?= $user["phone"] ?></p>
                                </div>

                                <div class="detail-item">
                                    <small class="text-muted">Date of Birth</small>
                                    <p class="mb-0 fw-bold" id="userDob">January 15, 1985</p>
                                </div>
                            </div>

                            <!-- Right Column - Additional Info -->
                            <div class="col-md-6">
                                <h5 class="card-title mb-4">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>Additional Information
                                </h5>

                                <div class="detail-item">
                                    <small class="text-muted">User ID</small>
                                    <p class="mb-0 fw-bold" id="userId"><?= $user["id"]?></p>
                                </div>

                                <div class="detail-item">
                                    <small class="text-muted">Registration Date</small>
                                    <p class="mb-0 fw-bold" id="regDate"><?= $user["created_at"] ?></p>
                                </div>

                                <div class="detail-item">
                                    <small class="text-muted">Last Login</small>
                                    <p class="mb-0 fw-bold" id="lastLogin">2 hours ago</p>
                                </div>

                                <div class="detail-item">
                                    <small class="text-muted">Account Status</small>
                                    <p class="mb-0">
                                        <span class="badge bg-success" id="accountStatus">Verified</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-outline-danger me-2">
                                <i class="fas fa-ban me-1"></i> Suspend
                            </button>
                            <a class="btn btn-outline-primary me-2" href="sendmeassagetouser.php">
                                <i class="fas fa-envelope me-1"></i> Message
                            </a>
                            <button class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit Profile
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Additional Sections (Tabs) -->
                <ul class="nav nav-tabs mt-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                            <i class="fas fa-history me-1"></i> Activity Log
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                            <i class="fas fa-file-alt me-1"></i> Documents
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="profileTabsContent">
                    <!-- Activity Log Tab -->
                    <div class="tab-pane fade show active" id="activity" role="tabpanel">
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <small>Logged in</small>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <small>Updated profile information</small>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <small>Changed password</small>
                                    <small class="text-muted">1 week ago</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <p class="text-muted">No documents uploaded yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS to load user data -->
    <script>
        // This would typically be populated from an API call
        document.addEventListener('DOMContentLoaded', function() {
            // Example of how you might populate the data
            // In reality, you would fetch this from your backend
            const userId = new URLSearchParams(window.location.search).get('id');

            if (userId) {
                // Fetch user data from API
                fetch(`/api/users/${userId}`)
                    .then(response => response.json())
                    .then(user => {
                        // Populate all fields
                        document.getElementById('userName').textContent = user.name;
                        document.getElementById('userStatus').textContent = user.status;
                        document.getElementById('userRole').textContent = user.role;
                        // ... populate all other fields
                    })
                    .catch(error => {
                        console.error('Error loading user:', error);
                    });
            }
        });
    </script>
</body>

</html>