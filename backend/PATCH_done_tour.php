<?php
// session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: PATCH');

require_once 'asset/config.php';

// if($_SESSION['tourist_id'] ?? null) {
//     // Tourist is logged in, proceed with the request
// } else {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in as tourist."]);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use PATCH."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));
if(empty($data->booking_request_id)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing Booking Request ID."]);
    exit();
}

$booking_request_id = $data->booking_request_id;
$update_fields = [];
$params_array = [];

if(isset($data->status)) {
    $update_fields[] = "status = ?";
    $params_array[] = $data->status;
}

if(empty($update_fields)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No fields to update."]);
    exit();
}

$update_sql = "UPDATE booking_history SET " . implode(", ", $update_fields) . " WHERE booking_request_id = ?";
$params_array[] = $booking_request_id;
$update_stmt = mysqli_prepare($con, $update_sql);
if(mysqli_stmt_execute($update_stmt, $params_array)) {
    echo json_encode(["status" => "success", "message" => "Booking request updated successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to update booking request."]);
}

?>