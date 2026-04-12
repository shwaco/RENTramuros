<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Method: POST');

require_once '../../../asset/connect_phpmyadmin.php';

$data = json_decode(file_get_contents("php://input"));

if(empty($data->first_name) || empty($data->last_name) || empty($data->email) || empty($data->password)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$first_name = $data->first_name;
$last_name = $data->last_name;
$email = $data->email;
$password = password_hash($data->password, PASSWORD_DEFAULT);

$check_sql = "SELECT guide_id FROM tour_guides WHERE email = ?";
$check_stmt = $conn->prepare($check_sql);
mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if(mysqli_stmt_num_rows($check_stmt) > 0) {
    echo json_encode(["status" => "error", "message" => "A tour guide with this email already exists."]);
    exit();
}

$insert_sql = "INSERT INTO tour_guides (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
mysqli_stmt_bind_param($insert_stmt, "ssss", $first_name, $last_name, $email, $password);
if(mysqli_stmt_execute($insert_stmt)) {
    echo json_encode(["status" => "success", "message" => "Tour guide added successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add tour guide."]);
}

?>