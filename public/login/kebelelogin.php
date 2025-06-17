<?php
session_start();

include "../../setup/dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hospitallogin"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM kebele_officers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $kebele = $result->fetch_assoc();
        echo $hospital["hospital_id"];
        if (password_verify($password, $kebele["password"])) {
            $_SESSION["kebele"] = $kebele["hospital_id"];
            header("location: ../../kebeledashboard/sidebar.php");
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .hospital-logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .hospital-logo i {
            font-size: 50px;
            color: #007bff;
        }

        .form-control {
            height: 45px;
            border-radius: 5px;
        }

        .btn-login {
            background-color: #007bff;
            color: white;
            height: 45px;
            border-radius: 5px;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #0069d9;
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="hospital-logo">
                <i class="fas fa-hospital"></i>
                <h2>kebele</h2>
                <p class="text-muted">Please login to access your account</p>
            </div>

            <form id="loginForm" action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="hospitallogin" class="btn btn-login w-100">Login</button>

                <div class="links">
                    <a href="forgethospital.php" id="forgotPassword">Forgot Password?</a>
                    <a href="#" id="createAccount">Create Account</a>
                </div>
            </form>


        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        // document.getElementById('togglePassword').addEventListener('click', function() {
        //     const password = document.getElementById('password');
        //     const icon = this.querySelector('i');

        //     if (password.type === 'password') {
        //         password.type = 'text';
        //         icon.classList.remove('fa-eye');
        //         icon.classList.add('fa-eye-slash');
        //     } else {
        //         password.type = 'password';
        //         icon.classList.remove('fa-eye-slash');
        //         icon.classList.add('fa-eye');
        //     }
        // });

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

        // // Forgot password link
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