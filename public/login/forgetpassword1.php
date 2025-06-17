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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Forgot Password</h2>

                         <p class="text-center">enter your email address to receive a reset password code </p>

                        <form action="forgetpassword.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="d-grid">
                                <button  type="submit" class="btn btn-primary" name="submit">Send Reset Code</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="userlogin.php" class="text-decoration-none">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>