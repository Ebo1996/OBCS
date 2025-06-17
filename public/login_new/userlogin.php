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
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-light: rgba(102, 126, 234, 0.8);
            --primary-dark: rgba(118, 75, 162, 0.8);
            --glass-white: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-white: rgba(255, 255, 255, 0.9);
            --text-light: rgba(255, 255, 255, 0.7);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url("https://ju.edu.et/agri/wp-content/uploads/sites/3/2022/11/1O2A3909-copy-1536x1024.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-white);
            overflow-x: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--glass-border);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--primary-gradient);
            opacity: 0.1;
            z-index: -1;
            transform: rotate(15deg);
            animation: gradientRotate 20s linear infinite;
        }

        @keyframes gradientRotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .login-container:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 45px;
            position: relative;
        }

        .login-header i {
            font-size: 72px;
            color: white;
            margin-bottom: 25px;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .login-header:hover i {
            transform: scale(1.1);
        }

        .login-header h2 {
            font-weight: 700;
            margin-bottom: 12px;
            color: white;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        .login-header p {
            color: var(--text-light);
            font-size: 1rem;
            margin-bottom: 0;
            line-height: 1.6;
        }

        .form-label {
            color: var(--text-white);
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: var(--text-white);
            padding: 14px 18px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            height: auto;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            color: var(--text-white);
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
            font-weight: 300;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            font-weight: 600;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            margin-top: 15px;
            transition: all 0.3s;
            letter-spacing: 1px;
            color: white;
            text-transform: uppercase;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            color: var(--text-light);
            font-size: 0.85rem;
            border-top: 1px solid var(--glass-border);
            padding-top: 20px;
            line-height: 1.6;
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .login-links a {
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .login-links a:hover {
            color: white;
            text-decoration: none;
            transform: translateX(3px);
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: var(--text-light);
            padding: 0 15px;
        }

        .btn-outline-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: var(--text-light);
            transition: all 0.3s;
        }

        .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .input-group> :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
            border-left: 1px solid var(--glass-border);
        }

        /* Floating animation */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-container {
                padding: 40px 25px;
                margin: 0 15px;
            }

            .login-header i {
                font-size: 60px;
                margin-bottom: 20px;
            }

            .login-header h2 {
                font-size: 1.8rem;
            }
        }

        /* Custom checkbox */
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
        }

        .form-check-input:checked {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
        }

        .form-check-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-circle floating"></i>
            <h2>WELCOME</h2>
            <p>Please enter your credentials to access your account</p>
        </div>

        <form id="loginForm" action="" method="POST">
            <input type="hidden" name="role" value="user">
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" id="username" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>

            <div class="d-grid gap-2 mb-3">
                <button type="submit" name="userlogin" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> SIGN IN
                </button>
            </div>

            <div class="login-links">
                <a href="forgetpassword1.php" id="forgotPassword">
                    <i class="fas fa-key"></i> Forgot Password?
                </a>
                <a href="../signUp.php" id="createAccount">
                    <i class="fas fa-user-plus"></i> Create Account
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
                this.setAttribute('aria-label', 'Hide password');
            } else {
                password.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
                this.setAttribute('aria-label', 'Show password');
            }
        });

        // Add focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-group-text').style.background = 'rgba(255, 255, 255, 0.2)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-group-text').style.background = 'rgba(255, 255, 255, 0.1)';
            });
        });

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    </script>
</body>

</html>