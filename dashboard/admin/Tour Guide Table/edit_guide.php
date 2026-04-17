<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Method: PATCH');

require_once '../../../asset/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if(empty($data->guide_id)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$guide_id = $data->guide_id;

$update_fields = [];
$params_array = [];

if(isset($data->first_name)) {
    $update_fields[] = "first_name = ?";
    $params_array[] = $data->first_name;
}

if(isset($data->last_name)) {
    $update_fields[] = "last_name = ?";
    $params_array[] = $data->last_name;
}

if(isset($data->email)) {
    $update_fields[] = "email = ?";
    $params_array[] = $data->email;
}

if(isset($data->password)) {
    $update_fields[] = "password = ?";
    $params_array[] = password_hash($data->password, PASSWORD_DEFAULT);
}

if(empty($update_fields)) {
    echo json_encode(["status" => "error", "message" => "No fields to update."]);
    exit();
}



$update_sql = "UPDATE tour_guides SET " . implode(", ", $update_fields) . " WHERE guide_id = ?";
$params_array[] = $guide_id;

$update_stmt = mysqli_prepare($con, $update_sql);

if(mysqli_stmt_execute($update_stmt, $params_array)) {
    if(mysqli_stmt_affected_rows($update_stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Tour guide updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No changes made to the tour guide."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update tour guide."]);
}

?>