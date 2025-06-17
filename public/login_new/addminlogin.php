<?php
    session_start(); // Start session for user login


    include '../../setup/dbconnection.php'; // Include database connection


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

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
                if ($user["role"] === "admin") {
                    header("Location: ../../ADMIN1/dashboard.php");
                } else {
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
        // }elseif($role == 'hospital'){
        //     $sql = "SELECT * FROM hospitals WHERE email = ?";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("s", $email);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        //     if($result->num_rows > 0){
        //             $hospital = $result->fetch_assoc();
        //             $_SESSION["id"]= $hospital["hospital_id"];
        //             $_SESSION["email"] = $hospital["email"];
        //             include "../hospitaldashboard/hospitalDashboard.php";

    }

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.5)), url("https://ju.edu.et/agri/wp-content/uploads/sites/3/2022/11/1O2A3909-copy-1536x1024.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header i {
            font-size: 60px;
            color: #fff;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .login-header h2 {
            font-weight: 700;
            margin-bottom: 10px;
            color: #fff;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .form-label {
            color: #fff;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgb(255, 255, 255);
            cursor: pointer;
            transition: all 0.3s;
        }

        .input-group-text:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            border: none;
            border-radius: 8px;
            margin-top: 10px;
            transition: all 0.3s;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #3a3f9e, #7a7feb);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .invalid-feedback {
            display: none;
            color: #ff6b6b;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }

        .toggle-password {
            cursor: pointer;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h2>ADMIN PORTAL</h2>
            <p>Secure access to the administration panel</p>
        </div>

        <form id="loginForm" action="" method="POST">
            <input type="hidden" name="role" value="admin">
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" id="username" placeholder="admin@example.com" required>
                </div>
                <div class="invalid-feedback">
                    Please enter a valid email address
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" name="password" class="form-control" id="password" placeholder="••••••••" required>
                    <span class="input-group-text toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div class="invalid-feedback">
                    Please enter your password
                </div>
            </div>

            <div class="d-grid gap-2 mb-3">
                <button type="submit" name="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt"></i> SIGN IN
                </button>
            </div>

            <div class="footer-text">
                <p>Unauthorized access is strictly prohibited</p>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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
    </script>

</body>

</html>