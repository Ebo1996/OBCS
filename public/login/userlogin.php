<?php
session_start(); // Start session for user login


include '../../setup/dbconnection.php'; // Include database connection


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userlogin"])) {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Prepare the SQL statement to prevent SQL Injection
    // if($role == 'user' || $role =='addmin'){
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user["password"])) {
            // Store user session
            $_SESSION["id"] = $user["id"];
            $_SESSION["role"] = $user["role"]; // Store role for admin/user handling
            $_SESSION["email"] = $user["email"];

            // Redirect based on user role
            if ($user["role"] === "user") {
                header("location: ../../userdashboard/user.php");
            }
            exit;
        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('User not found! Please sign up.');</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header i {
            font-size: 50px;
            color: #0d6efd;
            margin-bottom: 15px;
        }

        .form-control {
            height: 45px;
            border-radius: 5px;
        }

        .input-group-text {
            background-color: #f8f9fa;
        }

        .btn-login {
            height: 45px;
            font-weight: 600;
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-circle"></i>
                <h3>User Login</h3>
            </div>

            <form id="loginForm" action="" method="POST">
                <input type="hidden" name="role" value="user">
                <div class="mb-3">
                    <label for="email" class="form-label">email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="email" name="email" class="form-control" id="username" placeholder="Enter email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="userlogin" class="btn btn-primary btn-login w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>

                <div class="login-links">
                    <a href="forgetpassword.php" class="text-decoration-none" id="forgotPassword">Forgot Password?</a>
                    <a href="../signUp.php" class="text-decoration-none" id="createAccount">Create Account</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>





<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    // // Form submission
    // document.getElementById('loginForm').addEventListener('submit', function(e) {
    //     e.preventDefault();

    //     const username = document.getElementById('username').value;
    //     const password = document.getElementById('password').value;

    //     // Here you would typically send data to server for validation
    //     console.log('Login attempt:', {
    //         username,
    //         password
    //     });

    //     // For demo: Show success message if fields aren't empty
    //     if (username && password) {
    //         alert('Login successful (demo)\nUsername: ' + username);
    //     } else {
    //         alert('Please enter both username and password');
    //     }
    // });

    // // Forgot password link
    // document.getElementById('forgotPassword').addEventListener('click', function(e) {
    //     e.preventDefault();
    //     alert('Password reset link would be sent to your email');
    // });

    // // Create account link
    // document.getElementById('createAccount').addEventListener('click', function(e) {
    //     e.preventDefault();
    //     alert('Redirecting to account creation page');
    // });
</script>