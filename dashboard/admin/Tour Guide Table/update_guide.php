<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Method: PUT');

require_once '../../../asset/connect_phpmyadmin.php';

$data = json_decode(file_get_contents("php://input"));

if(empty($data->guide_id) || empty($data->first_name) || empty($data->last_name) || empty($data->email)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$guide_id = $data->guide_id;
$first_name = $data->first_name;
$last_name = $data->last_name;
$email = $data->email;

$update_sql = "UPDATE tour_guides SET first_name = ?, last_name = ?, email = ? WHERE guide_id = ?";
$update_stmt = $conn->prepare($update_sql);
mysqli_stmt_bind_param($update_stmt, "sssi", $first_name, $last_name, $email, $guide_id);

if(mysqli_stmt_execute($update_stmt)) {
    if(mysqli_stmt_affected_rows($update_stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Tour guide updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No tour guide found with the provided ID or no changes made."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update tour guide."]);
}

?>