<?php
// medssagetohoospital.php
include "../setup/dbconnection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// 1) Read & validate inputs
if (
  empty($_POST['hospital_id']) ||
  empty($_POST['subject']) ||
  empty($_POST['content'])
) {
  http_response_code(400);
  exit('Missing parameters.');
}

$hospitalId = (int) $_POST['hospital_id'];
$subject = trim($_POST['subject']);
$content = trim($_POST['content']);

// 2) Lookup hospital
$sql = "SELECT email, CONCAT(name) AS full_name
        FROM hospitals 
        WHERE hospital_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospitalId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  http_response_code(404);
  exit('Hospital not found.');
}
$hospital = $res->fetch_assoc();

// 3) Send e-mail
$mail = new PHPMailer(true);
try {
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'your@gmail.com';
  $mail->Password = 'your_app_password';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;

  $mail->setFrom('your@gmail.com', 'Certificate Office');
  $mail->addAddress($hospital['email'], $hospital['full_name']);

  $mail->Subject = $subject;
  $mail->isHTML(true);
  $mail->Body = "
      Hello <strong>{$hospital['full_name']}</strong>,<br><br>
      {$content}
    ";
  $mail->send();
} catch (Exception $e) {
  // You can choose to continue to save message even if mail fails,
  // or exit here. We'll continue.
}

// 4) Insert into messages table
//    Adjust column names to your schema!
$insert = "INSERT INTO hospital_messages (hospital_id, subject, message, sent_at)
           VALUES (?, ?, ?, NOW())";
$sth = $conn->prepare($insert);
$sth->bind_param("iss", $hospitalId, $subject, $content);

if ($sth->execute()) {
  echo "Message sent and saved successfully!";
} else {
  http_response_code(500);
  echo "Message saved failed: " . $sth->error;
}
