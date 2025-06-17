<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "../setup/dbconnection.php";

if($_SERVER["REQUEST_METHOD"] == "POST" ){
    $app_id = $_GET["app_id"];
    echo $app_id;
    $message = $_POST["message_of_rejection"];
    $sql = "SELECT * FROM applications WHERE id = ?";
   $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $email = $application["email"];
    $full_name = $application["first_name"]." ".$application["middle_name"]." ".$application["last_name"];
    $sql = "INSERT INTO messages (user_id, title, body) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $application["user_id"], "rejected application", $message);

    if($stmt->execute()){
        $sql = "DELETE FROM applications WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $app_id);
        if($stmt->execute()){
         
            $mail = new PHPMailer(true);
            try{
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'bonsadaba8@gmail.com';
                $mail->Password = 'nfcg vsoa oyhm etyv';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('bonsadaba8@gmail.com', 'ifa bula certificate office');
                $mail->addAddress($email, $full_name);
                $mail->Subject = 'your application has been rejected';
                $mail->Body = "hello $full_name, your application has been rejected. reason: $message";
                $mail->send();
                echo "application rejected";
            }catch(Exception $e){
                echo "application regected but email failed: {$mail->ErrorInfo}";
                
            }
            }
        }
    }








?>


