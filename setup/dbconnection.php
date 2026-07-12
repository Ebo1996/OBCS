<?php
$hostName = "sql305.infinityfree.com";
$username = "if0_42392408";
$password = "XWC2i6RYmJciclb";
$database = "if0_42392408_obcsdb";

$conn = new mysqli($hostName, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
