<?php
include "../setup/dbconnection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

$hospital_name = trim($_POST["hospitalName"]);
$address = trim($_POST["address"]);
$email = trim($_POST["email"]);
$username = trim($_POST["username"]);
$phone = trim($_POST["phone"]);
$password = trim($_POST["password"]);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO hospitals (name, email, phone, address, username, password) VALUES (?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $hospital_name, $email, $phone, $address, $username, $hashed_password);
if ($stmt->execute()) {
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bonsadaba8@gmail.com';
    $mail->Password = 'nfcg vsoa oyhm etyv';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('bonsadaba8@gmail.com', 'Certificate Office');
    $mail->addAddress($email, $hospital_name);

    $mail->Subject = 'WELL COME  TO OUR SYSTEM!';
    $mail->Body = "Hello {$hospital_name}n\nYour account  is created. this is your password of our sytem  {$password}\n\n you can chnage your password from your dashborad KEEP IT SECURE !!! \n\nThank you!";

    $mail->send();
    echo "success";
  } catch (Exception $e) {
    echo "Certificate approved, but email failed: {$mail->ErrorInfo}";
  }
}




?>