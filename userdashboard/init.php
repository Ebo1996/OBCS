<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
}


if (!isset($_SESSION["email"]) || !isset($_SESSION["id"])) {
    header("Location: login.php");
    exit(); // important to stop execution
}

?> 