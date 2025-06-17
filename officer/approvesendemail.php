<?php
session_start(); // Add this at the very top
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


include '../setup/dbconnection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate application ID
if (!isset($_POST['app_id']) || !is_numeric($_POST['app_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid application ID']);
    exit;
}

$application_id = (int)$_POST['app_id'];

// Start transaction
$conn->begin_transaction();

try {
    function generate_id()
    {
        global $conn;
        $year = date('y');
        $sql = "SELECT MAX(id) AS max_id FROM certificates";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $next_id = $row["max_id"] + 1;
        return "IBBC-" . str_pad($next_id, 4, '0', STR_PAD_LEFT) . "/" . $year;
    }
    // 1. Get application data
    $sql_select = "SELECT * FROM applications WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    if (!$stmt_select) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt_select->bind_param("i", $application_id);
    if (!$stmt_select->execute()) {
        throw new Exception("Execute failed: " . $stmt_select->error);
    }

    $result = $stmt_select->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Application not found");
    }

    $application = $result->fetch_assoc();
    $full_name = trim("{$application['first_name']} {$application['middle_name']} {$application['last_name']}");
    $certificate_id = generate_id();
    // 2. Insert into certificates table (simplified version)
    $sql_insert = "INSERT INTO certificates 
                  (certificate_id, user_id, first_name, middle_name, last_name, dob, gender, 
                   place_of_birth, father_name, mother_name, current_address, issued_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt_insert = $conn->prepare($sql_insert);
    if (!$stmt_insert) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $approved_by = $_SESSION['user_id'] ?? 1; // Fallback to admin ID 1 if session not set

    $stmt_insert->bind_param(
        "sisssssssssi",
        $certificate_id,
        $application['user_id'],
        $application['first_name'],
        $application['middle_name'],
        $application['last_name'],
        $application['dob'],
        $application['gender'],
        $application['place_of_birth'],
        $application['father_name'],
        $application['mother_name'],
        $application['current_address']
    );

    if (!$stmt_insert->execute()) {
        throw new Exception("Insert failed: " . $stmt_insert->error);
    }

    // 3. Delete from applications table
    $sql_delete = "DELETE FROM applications WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    if (!$stmt_delete) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt_delete->bind_param("i", $application_id);
    if (!$stmt_delete->execute()) {
        throw new Exception("Delete failed: " . $stmt_delete->error);
    }

    // 4. Add notification
    $message_title = "Application Approved";
    $message_body = "Your birth certificate application has been approved.";

    $sql_message = "INSERT INTO messages (user_id, title, body) VALUES (?, ?, ?)";
    $stmt_message = $conn->prepare($sql_message);
    if (!$stmt_message) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt_message->bind_param("iss", $application['user_id'], $message_title, $message_body);
    if (!$stmt_message->execute()) {
        throw new Exception("Message insert failed: " . $stmt_message->error);
    }

    // 5. Send email
    $mail_sent = false;
    $mail_error = '';

    if (!empty($application['email'])) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bonsadaba8@gmail.com';
            $mail->Password = 'nfcg vsoa oyhm etyv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('bonsadaba8@gmail.com', 'Certificate Office');
            $mail->addAddress($application['email'], $full_name);
            $mail->isHTML(true);
            $mail->Subject = 'Application Approved';
            $mail->Body = "Dear $full_name, your application has been approved.";

            $mail->send();
            $mail_sent = true;
        } catch (Exception $e) {
            $mail_error = $e->getMessage();
        }
    }

    // Commit if everything succeeded
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Application approved successfully',
        'email_sent' => $mail_sent,
        'email_error' => $mail_error
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} finally {
    // Close all statements
    if (isset($stmt_select)) $stmt_select->close();
    if (isset($stmt_insert)) $stmt_insert->close();
    if (isset($stmt_delete)) $stmt_delete->close();
    if (isset($stmt_message)) $stmt_message->close();
    $conn->close();
}
