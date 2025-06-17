<?php
include '../setup/dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $body = trim($_POST["body"]);
    $type = $_POST["type"];

    if ($type === "announcement") {
        $sql = "INSERT INTO announcements (title, body, posted_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $body);
    } elseif ($type === "message") {
        $user_id = $_POST["user_id"];
        $sql = "INSERT INTO messages (user_id, title, body, sent_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $title, $body);
    } else {
        die("Invalid submission.");
    }

    if ($stmt->execute()) {
        echo "<script>alert('Submission successful!'); window.location.href='admin_announcement.php';</script>";
    } else {
        echo "<script>alert('Error: Failed to submit.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
    header("location:sidebar.php");
}
