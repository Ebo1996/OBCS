<?php
header('Content-Type: application/json');
include "../setup/dbconnection.php";
$response =["success" => false, "message"=> ""];
if(!isset($_POST["kebele_id"]) || empty($_POST["kebele_id"])){
    $response["message"] = " kebele id number is requred";
    echo json_encode($response);
    exit;
}
$kebele_id = $_POST["kebele_id"];
$sql="SELECT * FROM kebele_ids WHERE kebele_id_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$kebele_id);
$stmt->execute();
$result= $stmt->get_result();

if($result->num_rows == 0){
    $response["message"]= "identity not found.";
    echo json_encode($response);
    exit;
}

$kebele_id = $result->fetch_assoc();

$response["success"]= true;
$response["kebele_id"]= $kebele_id;
echo json_encode($response);
?>