<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-ControlAllow-Method: POST');

session_start();
require_once '../../../asset/config.php';

$data = json_decode(file_get_contents("php://input"));
if(empty($data->attraction_name) || empty($data->description) || empty($data->entrance_fee) || empty($data->operating_hours) || empty($data->image_file)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$attraction_type = $data->attraction_type;
$attraction_name = $data->attraction_name;
$description = $data->description;
$schedule = $data->schedule;
$fee = $data->fee;
$main_img = $data->main_img;
$mini_one_img = $data->mini_one_img;
$mini_two_img = $data->mini_two_img;
$rec_img = $data->rec_img;

$insert_sql = "INSERT INTO Attractions (attraction_name, description, schedule, fee, main_img, mini_one_img, mini_two_img, rec_img) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = mysqli_prepare($con, $insert_sql);
mysqli_stmt_bind_param($insert_stmt, "sssss", $attraction_name, $description, $schedule, $fee, $main_img, $mini_one_img, $mini_two_img, $rec_img);

if(mysqli_stmt_execute($insert_stmt)) {
    echo json_encode(["status" => "success", "message" => "Attraction added successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add attraction."]);
}
?>