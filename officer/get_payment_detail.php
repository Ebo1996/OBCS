<?php
include "../setup/dbconnection.php";

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM payments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => true,
            'payment' => $result->fetch_assoc()
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
