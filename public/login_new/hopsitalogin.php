<?php
session_start();

include "../../setup/dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hospitallogin"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM hospitals WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $hospital = $result->fetch_assoc();
        echo $hospital["hospital_id"];
        if ($password == $hospital["password"]) {
            $_SESSION["hospital_id"] = $hospital["hospital_id"];
            header("location: ../../hospitaldashboard/hospitalDashboard.php");
        } else {
            echo 'incorrect password';

            // $hashed = password_hash("1111", PASSWORD_DEFAULT);
            // echo $hashed;


        }
    } else {
        die("no hospital is found whith this username  ");
    }


    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Portal - User Login</title>
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
            margin-bottom: 0;
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
            color: white;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #3a3f9e, #7a7feb);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 15px;
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .links a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
        }

        #togglePassword {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
        }

        #togglePassword:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
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
            <i class="fas fa-hospital"></i>
            <h2>HOSPITAL LOGIN</h2>
            <p>Please login to access your account</p>
        </div>

        <form id="loginForm" action="" method="POST">
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                    <button class="btn" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-grid gap-2 mb-3">
                <button type="submit" name="hospitallogin" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> LOGIN
                </button>
            </div>

            <div class="links">
                <a href="forgethospital.php" id="forgotPassword">
                    <i class="fas fa-key"></i> Forgot Password?
                </a>
                <a href="#" id="createAccount">
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
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // // Form submission
        // document.getElementById('loginForm').addEventListener('submit', function(e) {
        //     e.preventDefault();

        //     const username = document.getElementById('username').value;
        //     const password = document.getElementById('password').value;

        //     // Here you would typically send this data to a server for validation
        //     console.log('Login attempt with:', { username, password });

        //     // For demo purposes, show an alert
        //     alert('Login functionality would be implemented here. Check console for credentials.');
        // });

        // Forgot password link
        // document.getElementById('forgotPassword').addEventListener('click', function(e) {
        //     e.preventDefault();
        //     alert('Forgot password functionality would be implemented here.');
        // });

        // // Create account link
        // document.getElementById('createAccount').addEventListener('click', function(e) {
        //     e.preventDefault();
        //     alert('Account creation functionality would be implemented here.');
        // });
    </script>
</body>

</html>