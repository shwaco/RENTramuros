<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-ControlAllow-Method: POST');
require_once '../../../asset/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if(empty($data->attraction_name) || empty($data->description) || empty($data->entrance_fee) || empty($data->operating_hours) || empty($data->image_file)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$attraction_name = $data->attraction_name;
$description = $data->description;
$entrance_fee = $data->entrance_fee;
$operating_hours = $data->operating_hours;
$image_file = $data->image_file;

$insert_sql = "INSERT INTO Attractions (attraction_name, description, entrance_fee, operating_hours, image_file) VALUES (?, ?, ?, ?, ?)";
$insert_stmt = mysqli_prepare($con, $insert_sql);
mysqli_stmt_bind_param($insert_stmt, "sssss", $attraction_name, $description, $entrance_fee, $operating_hours, $image_file);

if(mysqli_stmt_execute($insert_stmt)) {
    echo json_encode(["status" => "success", "message" => "Attraction added successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add attraction."]);
}
?>