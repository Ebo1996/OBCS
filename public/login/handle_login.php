<?php
session_start();
include "../../setup/dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($role == "admin" || $role == "user") {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {
                if ($user["role"] == "admin") {
                    $_SESSION['admin'] = $user['email'];
                    echo "redirect:admin/admin.php";
                    exit;
                } elseif ($user["role"] == "user") {
                    $_SESSION['user'] = $user['email'];
                    echo "redirect:../../userdashboard/user.php";
                    exit;
                }
            } else {
                echo "error:Incorrect password";
            }
        } else {
            echo "error:User not found!";
        }
    } else {
        echo "error:Invalid role";
    }
}
