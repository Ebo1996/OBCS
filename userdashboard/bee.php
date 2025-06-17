<?php
session_start();
include '../setup/dbconnection.php';

// Redirect if not logged in
if (!isset($_SESSION["email"]) || !isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION["email"];
$user_id = $_SESSION["id"];
$message = "";

// Get user data
$sql = "SELECT * FROM users WHERE email = ? AND id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $oldpassword = trim($_POST["oldpassword"]);
    $newpassword = trim($_POST["newpassword"]);
    $confirmpassword = trim($_POST["confirmpassword"]);
    $phone = trim($_POST["phone"]);

    // Validate current password
    if (!password_verify($oldpassword, $user["password"])) {
        $message = "<div class='alert alert-danger'>Incorrect current password</div>";
    } 
    // Validate new password if provided
    elseif (!empty($newpassword) && $newpassword !== $confirmpassword) {
        $message = "<div class='alert alert-danger'>New passwords don't match</div>";
    }
    // Validate password strength if changing
    elseif (!empty($newpassword) && strlen($newpassword) < 8) {
        $message = "<div class='alert alert-danger'>Password must be at least 8 characters</div>";
    } else {
        // Prepare update query
        $updateFields = [];
        $params = [];
        $types = "";
        
        // Update phone number
        $updateFields[] = "phone = ?";
        $params[] = $phone;
        $types .= "s";
        
        // Update password if provided
        if (!empty($newpassword)) {
            $hashedpassword = password_hash($newpassword, PASSWORD_DEFAULT);
            $updateFields[] = "password = ?";
            $params[] = $hashedpassword;
            $types .= "s";
        }
        
        // Add user ID to params
        $params[] = $user_id;
        $types .= "i";
        
        // Build and execute query
        $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            $_SESSION["success_message"] = "Profile updated successfully!";
            header("Location: user.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Error updating profile: " . $conn->error . "</div>";
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
    <style>
        .profile-img-container {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            position: relative;
        }
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card {
            max-width: 500px;
            margin: 30px auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
        }
        #passwordSection {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card p-4">
            <?php echo $message; ?>
            
            <div class="text-center mb-4">
                <div class="profile-img-container">
                    <img src="../assets/uploads/<?php echo htmlspecialchars($user["profile_image"] ?? 'default.png'); ?>" 
                         alt="Profile Image" 
                         class="profile-img">
                </div>
                <h4><?php echo htmlspecialchars($user["first_name"] . " " . $user["last_name"]); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($user["email"]); ?></p>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Phone Number</label>
                <input type="text" class="form-control bg-light" 
                       value="<?php echo htmlspecialchars($user["phone"] ?? 'Not set'); ?>" disabled>
            </div>

            <button type="button" class="btn btn-primary w-100 mb-3" id="editProfileBtn">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </button>
            
            <div id="passwordSection" class="d-none">
                <form action="editprofile.php" method="post">
                    <div class="mb-3">
                        <label for="phone" class="form-label">New Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="<?php echo htmlspecialchars($user["phone"] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="oldpassword" class="form-label">Current Password</label>
                        <input type="password" id="oldpassword" class="form-control" 
                               name="oldpassword" required placeholder="Enter your current password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="newpassword" class="form-label">New Password</label>
                        <input type="password" id="newpassword" class="form-control" 
                               name="newpassword" placeholder="Leave blank to keep current password">
                        <div class="form-text">Minimum 8 characters</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirmpassword" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirmpassword" class="form-control" 
                               name="confirmpassword" placeholder="Confirm new password">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" name="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                        <button type="button" id="cancelEdit" class="btn btn-outline-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editBtn = document.getElementById('editProfileBtn');
            const cancelBtn = document.getElementById('cancelEdit');
            const editSection = document.getElementById('passwordSection');
            
            // Toggle edit section
            editBtn.addEventListener('click', function() {
                editSection.classList.remove('d-none');
                editBtn.classList.add('d-none');
            });
            
            // Cancel editing
            cancelBtn.addEventListener('click', function() {
                editSection.classList.add('d-none');
                editBtn.classList.remove('d-none');
            });
            
            // Password validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const newPassword = document.getElementById('newpassword').value;
                const confirmPassword = document.getElementById('confirmpassword').value;
                
                if (newPassword !== '' && newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('New passwords do not match!');
                }
                
                if (newPassword !== '' && newPassword.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters!');
                }
            });
        });
    </script>
</body>
</html>