<?php
include "../setup/dbconnection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
$user_id = $_REQUEST["user_id"];
$subject = $_REQUEST["subject"];
$content = $_REQUEST["content"];

$sql = "SELECT email , CONCAT(first_name, middle_name, last_name) AS full_name FROM users WHERE ID = $user_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();

}


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
  $mail->addAddress($user["email"], $user["full_name"]);

  $mail->Subject = "{$subject}";
  $mail->isHTML(true);
  $mail->Body = "Hello <strong>{$user["full_name"]}</strong><br><br>{$content}";


  $mail->send();


  $notify = "INSERT INTO messages (user_id, title, body) VALUES (?,?,?)";

  $title = "approved application";
  $msg = "Your certificate application has been approved!";
  $stmt = $conn->prepare($notify);
  $stmt->bind_param("iss", $user_id, $subject, $content);
  $stmt->execute();
  echo "Certificate approved and email sent successfully!";
} catch (Exception $e) {
  echo "Certificate approved, but email failed: {$mail->ErrorInfo}";
}
?>