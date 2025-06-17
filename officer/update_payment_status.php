<?php
include "../setup/dbconnection.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['status'])) {
    $id = $data['id'];
    $status = $data['status'];
    $certificate_id = $data['certificate_id'];
    $sql = "UPDATE certificates SET payment_status = ? WHERE certificate_id = ?";
    $certstmt = $conn->prepare($sql);
    $certstmt->bind_param('ss', $status, $certificate_id);
    $certstmt->execute();
    $certstmt->close();
    $sql = "INSERT INTO approved_payments (payment_id, full_name, certificate_id, phone, amount, payment_IMG)
  SELECT id, full_name, certificate_id, phone, amount, payment_IMG
FROM payments
WHERE id = ?;
";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $sql = "DELETE FROM payments WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
