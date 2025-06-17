<?php
$hostName = "localhost";
$username = "root";
$password = "";
$database = "birth_certificate_database";

$conn = new mysqli($hostName, $username, $password, $database );
if ($conn->connect_error) {
  die("connection faield: " . $conn->connect_error);
}
