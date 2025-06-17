<?php
// session_start();
include "../setup/dbconnection.php";
 $sql = "SELECT * FROM officers";
 $sql = $conn->prepare($sql);
 $sql->execute();
 $result = $sql->get_result();
 if( $result->num_rows > 0){
     $officer = $result->fetch_assoc();
     
 }else{

     echo "use not found";
    echo $_SESSION["officer_id"];
    echo $_SESSION["officer_id"];
}
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Editing</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .profile-card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }

        .profile-header {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-pic:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .edit-icon {
            position: absolute;
            bottom: 30px;
            right: calc(50% - 80px);
            background: var(--secondary-color);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            border: 3px solid white;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 146, 79, 0.25);
        }

        .btn-save {
            background-color: var(--secondary-color);
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background-color: #0b7a40;
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .profile-body {
            padding: 2rem;
            background-color: white;
        }

        .input-group-text {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .password-toggle {
            cursor: pointer;
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="../assets//uploads/1746019401_photo_2025-04-26_22-11-24.jpg" alt="Profile Picture" class="profile-pic" id="profilePicture">
                        <div class="edit-icon" id="changePhotoBtn">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h3>Edit Profile</h3>
                    </div>
                    <div class="profile-body">
                        <form id="profileForm">
                            <div class="mb-4">
                                <label for="fullName" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" value="<?php echo $officer['full_name']; ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" placeholder="Enter your email" value="<?php echo $officer['email'];?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" value="<?php echo $officer['phone']?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" placeholder="Enter new password">
                                    <button class="btn password-toggle" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Leave blank to keep current password</small>
                            </div>

                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                                    <button class="btn password-toggle" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="button" class="btn btn-outline-secondary me-md-2">Cancel</button>
                                <button type="submit" class="btn btn-save text-white">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for changing profile picture -->
    <div class="modal fade" id="profilePicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-3">
                            <img src="https://via.placeholder.com/150" id="currentProfilePic" class="rounded-circle" width="150" height="150">
                        </div>
                        <input type="file" class="form-control" id="profilePicUpload" accept="image/*">
                        <small class="text-muted mt-2">Recommended size: 150x150 pixels</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProfilePicBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modal
            const profilePicModal = new bootstrap.Modal(document.getElementById('profilePicModal'));

            // Open modal when change photo button is clicked
            document.getElementById('changePhotoBtn').addEventListener('click', function() {
                profilePicModal.show();
            });

            // Preview image when file is selected
            document.getElementById('profilePicUpload').addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        document.getElementById('currentProfilePic').src = event.target.result;
                    }

                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Save profile picture
            document.getElementById('saveProfilePicBtn').addEventListener('click', function() {
                const newPicSrc = document.getElementById('currentProfilePic').src;
                document.getElementById('profilePicture').src = newPicSrc;
                profilePicModal.hide();

                // Here you would typically upload the image to your server
                alert('Profile picture updated successfully!');
            });

            // Toggle password visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            // Toggle confirm password visibility
            document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
                const confirmPasswordInput = document.getElementById('confirmPassword');
                const icon = this.querySelector('i');
                if (confirmPasswordInput.type === 'password') {
                    confirmPasswordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    confirmPasswordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            // Handle form submission
            document.getElementById('profileForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form values
                const fullName = document.getElementById('fullName').value;
                const email = document.getElementById('email').value;
                const phone = document.getElementById('phone').value;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                // Validate passwords if they were entered
                if (password || confirmPassword) {
                    if (password !== confirmPassword) {
                        alert('Passwords do not match!');
                        return;
                    }

                    if (password.length < 8) {
                        alert('Password must be at least 8 characters long!');
                        return;
                    }
                }

                // Here you would typically send this data to your server
                console.log('Profile updated:', {
                    fullName,
                    email,
                    phone,
                    password: password ? '*****' : 'unchanged'
                });

                // Show success message
                alert('Profile updated successfully!');
            });
        });
    </script>
</body>

</html>