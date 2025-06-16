<?php
include "../setup/dbconnection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendmessage($email, $name, $password)
{
  require '../vendor/autoload.php';
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
    $mail->addAddress($email, $name);

    $mail->Subject = 'WELL COME  TO OUR SYSTEM!';
    $mail->addAddress($email, $name);
    $mail->Body = "Hello {$name},\n\nYour account is created. This is your system password: {$password}\nYou can change your password from your dashboard. KEEP IT SECURE!\n\nThank you!";

    $mail->send();
    echo "success";
  } catch (Exception $e) {
    echo "Certificate approved, but email failed: {$mail->ErrorInfo}";
  }
}

function generateRandomPassword($length = 8)
{
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*';
  return substr(str_shuffle($chars), 0, $length);
}

$password = generateRandomPassword();

if (isset($_POST["register_officer"])) {
  $fullName = $_POST["full_name"];
  $email = $_POST["email"];
  $username = $_POST["username"];
  $phone = $_POST["phone"];
  $password = generateRandomPassword();
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO officers (full_name, email, username, phone, password ) VALUES (?,?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $fullName, $email, $username, $phone, $hashed_password);
  if ($stmt->execute()) {
    sendmessage($email, $fullName, $password);

    echo "
    <div id='successPopup' style='
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #d1e7dd;
      color: #0f5132;
      padding: 15px 20px;
      border: 1px solid #badbcc;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 9999;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
    '>
      <span style='font-weight:bold;'>✓</span> Officer registered successfully!
    </div>
    <script>
      setTimeout(function() {
        var popup = document.getElementById('successPopup');
        if (popup) popup.remove();
      }, 3000);
    </script>
    ";
  }
}

if (isset($_POST["register_kebele_officer"])) {
  $fullName = $_POST["full_name"];
  $email = $_POST["email"];
  $username = $_POST["username"];
  $phone = $_POST["phone"];
  $password = generateRandomPassword();
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO kebele_officers (full_name, email, username, phone, password ) VALUES (?,?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $fullName, $email, $username, $phone, $hashed_password);
  if ($stmt->execute()) {
    sendmessage($email, $fullName, $password);

    echo "
    <div id='successPopup' style='
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #d1e7dd;
      color: #0f5132;
      padding: 15px 20px;
      border: 1px solid #badbcc;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 9999;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
    '>
      <span style='font-weight:bold;'>✓</span> Kebele admin registered successfully!
    </div>
    <script>
      setTimeout(function() {
        var popup = document.getElementById('successPopup');
        if (popup) popup.remove();
      }, 3000);
    </script>
    ";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Role</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
    }

    body {
      background-color: var(--light-bg);
      padding: 20px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-container {
      background-color: white;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
      max-width: 650px;
      width: 100%;
      margin: 2rem auto;
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 1.8rem;
      color: var(--primary-color);
      font-weight: 600;
    }

    .role-section h4 {
      color: var(--primary-color);
      font-weight: 500;
      margin-bottom: 1.5rem;
    }

    .btn-create {
      width: 100%;
      padding: 12px;
      font-weight: 500;
      margin-top: 1.5rem;
      background-color: var(--secondary-color);
      border: none;
      transition: all 0.3s ease;
    }

    .btn-create:hover {
      background-color: #0b7a41;
      transform: translateY(-2px);
    }

    .role-tabs {
      margin-bottom: 2rem;
      border-bottom: 1px solid #dee2e6;
      padding-bottom: 1rem;
    }

    .role-tabs .btn {
      width: 48%;
      font-weight: 500;
      padding: 10px 0;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .role-tabs .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .role-tabs .btn-outline-primary:hover,
    .role-tabs .btn-outline-primary.active {
      background-color: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
    }

    .width-100 {
      width: 800px;
    }

    .form-label {
      font-weight: 500;
      margin-bottom: 8px;
      color: var(--primary-color);
    }

    .form-control {
      padding: 12px;
      border-radius: 6px;
      border: 1px solid #ced4da;
      transition: border-color 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.25rem rgba(13, 146, 79, 0.25);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .role-section {
      display: none;
      animation: fadeIn 0.4s ease-out;
    }

    .role-section.active {
      display: block;
    }
  </style>
</head>

<body>
  <div class="container ">
    <div class="form-container width-100">
      <h2>Create Role</h2>
      <div class="d-flex justify-content-between role-tabs">
        <button type="button" class="btn btn-outline-primary me-2 active" id="officerButton">Officer</button>
        <button type="button" class="btn btn-outline-primary" id="kebeleAdminButton">Kebele Admin</button>
      </div>

      <!-- Officer Role Section -->
      <div class="role-section active" id="officerSection">
        <h4 class="text-center">Officer Information</h4>
        <form id="officerForm" action="createrole.php" method="POST">
          <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter full name"
              required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
          </div>
          <input type="hidden" name="roleType" value="officer">
          <button type="submit" name="register_officer" class="btn btn-primary btn-create">Register Officer</button>
        </form>
      </div>

      <!-- Kebele Admin Role Section -->
      <div class="role-section" id="kebeleAdminSection">
        <h4 class="text-center">Kebele Admin Information</h4>
        <form id="kebeleAdminForm" action="createrole.php" method="POST">
          <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter full name"
              required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
          </div>
          <input type="hidden" name="roleType" value="kebeleAdmin">
          <button type="submit" name="register_kebele_officer" class="btn btn-primary btn-create">Register Kebele
            Admin</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Toggle between Officer and Kebele Admin sections
    document.addEventListener('DOMContentLoaded', function () {
      const officerButton = document.getElementById('officerButton');
      const kebeleAdminButton = document.getElementById('kebeleAdminButton');
      const officerSection = document.getElementById('officerSection');
      const kebeleAdminSection = document.getElementById('kebeleAdminSection');

      officerButton.addEventListener('click', () => {
        officerSection.classList.add('active');
        kebeleAdminSection.classList.remove('active');
        officerButton.classList.add('active');
        kebeleAdminButton.classList.remove('active');
      });

      kebeleAdminButton.addEventListener('click', () => {
        kebeleAdminSection.classList.add('active');
        officerSection.classList.remove('active');
        kebeleAdminButton.classList.add('active');
        officerButton.classList.remove('active');
      });

      // Auto-close success message after 3 seconds
      const successPopup = document.getElementById('successPopup');
      if (successPopup) {
        setTimeout(() => {
          successPopup.remove();
        }, 3000);
      }
    });
  </script>
</body>

</html>