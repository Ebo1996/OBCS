<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

session_start();
include "../../setup/dbconnection.php"; // Your database connection file\
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)){
    $email = $_POST["email"];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_assoc();
    if($users["email"]){
        $code = rand(100000,999999);
        $newpassword = password_hash($code, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newpassword, $email);
        if($stmt->execute()){
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username ='bonsadaba8@gmail.com';
            $mail->Password = 'nfcg vsoa oyhm etyv';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('bonsadaba8@gmail.com', 'ifa bula certificate office');
            $mail->addAddress($email);
            $mail->Subject = 'password reset code';
            $mail->Body = 'hello, your password reset code is'.$code;
            $mail->send();
            echo "<script>alert('password reset code has been sent to your email');<script>";

            $notify = "INSERT INTO messages (user_id, title, body) VALUES (?,?,?)";
            $title = "your password was reset";
            $msg = "your password was and the code sent to your email Please for security purpose change your password to remmembered and strong one";
            $stmt = $conn->prepare($notify);
            $stmt->bind_param("iss", $users['id'], $title, $msg);
            $stmt->execute();

            header("location: userlogin.php");


        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .back-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: #fff;
            text-decoration: underline;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
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
            <i class="fas fa-key"></i>
            <h2>FORGOT PASSWORD</h2>
            <p>Enter your email address to receive a reset password code</p>
        </div>

        <form action="forgetpassword.php" method="POST">
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-login" name="submit">
                    <i class="fas fa-paper-plane"></i> SEND RESET CODE
                </button>
            </div>

            <div class="footer-text">
                <a href="userlogin.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>