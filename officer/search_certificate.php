<?php
header('Content-Type: application/json');
include '../setup/dbconnection.php';

$response = ["success" => false, "message" => ""];

if (!isset($_POST["certificate_id"]) || empty($_POST["certificate_id"])) {
    $response["message"] = "Certificate ID is required.";
    echo json_encode($response);
    exit;
}

$certificate_id = $_POST["certificate_id"];

// Fetch birth record
$sql = "SELECT * FROM birth_records WHERE record_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $certificate_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $response["message"] = "Certificate not found.";
    echo json_encode($response);
    exit;
}

$birth_record = $result->fetch_assoc();

// Fetch hospital info
$hospital = [];
$hospital_id = $birth_record["hospital_id"] ?? null;

if ($hospital_id) {
    $sql = "SELECT * FROM hospitals WHERE hospital_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $hospital = $result->fetch_assoc();
    }
}

$response["success"] = true;
$response["birth_record"] = $birth_record;
$response["hospital"] = $hospital;
echo json_encode($response);
