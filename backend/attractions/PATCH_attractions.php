<?php
// session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: PATCH');

require_once '../../../asset/config.php';

// if($_SESSION['admin_id'] ?? null) {
//     // Admin is logged in, proceed with the request
// } else {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in as admin."]);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->attraction_id)) {
    echo json_encode(["status" => "error", "message" => "Missing Attraction ID."]);
    exit();
}

$attraction_id = $data->attraction_id;

$update_fields = [];
$params_array = [];

if(isset($data->attraction_name)) {
    $update_fields[] = "attraction_name = ?";
    $params_array[] = $data->attraction_name;
}

if(isset($data->description)) {
    $update_fields[] = "description = ?";
    $params_array[] = $data->description;
}

if(isset($data->fee)) {
    $update_fields[] = "fee = ?";
    $params_array[] = $data->fee;
}

if(isset($data->schedule)) {
    $update_fields[] = "schedule = ?";
    $params_array[] = $data->schedule;
}

if(isset($data->main_img)) {
    $update_fields[] = "main_img = ?";
    $params_array[] = $data->main_img;
}

if(isset($data->mini_one_img)) {
    $update_fields[] = "mini_one_img = ?";
    $params_array[] = $data->mini_one_img;
}

if(isset($data->mini_two_img)) {
    $update_fields[] = "mini_two_img = ?";
    $params_array[] = $data->mini_two_img;
}

if(isset($data->rec_img)) {
    $update_fields[] = "rec_img = ?";
    $params_array[] = $data->rec_img;
}

if(empty($update_fields)) {
    echo json_encode(["status" => "error", "message" => "No fields to update."]);
    exit();
}

$update_sql = "UPDATE Attractions SET " . implode(", ", $update_fields) . " WHERE attraction_id = ?";
$params_array[] = $attraction_id;

$update_stmt = mysqli_prepare($con, $update_sql);

if(mysqli_stmt_execute($update_stmt, $params_array)) {
    if(mysqli_stmt_affected_rows($update_stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Attraction updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No attraction found with the provided ID or no changes made."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update attraction."]);
}