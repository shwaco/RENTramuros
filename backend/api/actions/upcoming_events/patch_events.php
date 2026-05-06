<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PATCH');

require_once '../../../config/config.php';
/** @var mysqli $con */

if ($_SESSION['admin_id'] ?? null) {
    // Admin is logged in, proceed with the request
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in as admin."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->event_id)) {
    echo json_encode(["status" => "error", "message" => "Missing Event ID."]);
    exit();
}

$event_id = $data->event_id;

$update_fields = [];
$params_array = [];

if(isset($data->event_name)) {
    $update_fields[] = "event_name = ?";
    $params_array[] = $data->event_name;
}

if(isset($data->description)) {
    $update_fields[] = "description = ?";
    $params_array[] = $data->description;
}

if(isset($data->schedule)) {
    $update_fields[] = "schedule = ?";
    $params_array[] = $data->schedule;
}

if(isset($data->main_img)) {
    $update_fields[] = "main_img = ?";
    $params_array[] = $data->main_img;
}

if(empty($update_fields)) {
    echo json_encode(["status" => "error", "message" => "No fields to update."]);
    exit();
}

$sql = "UPDATE upcoming_events SET " . implode(", ", $update_fields) . " WHERE event_id = ?";
$params_array[] = $event_id;

$update_stmt = mysqli_prepare($con, $sql);

if(mysqli_stmt_execute($update_stmt, $params_array)) {
    if(mysqli_stmt_affected_rows($update_stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Event updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No event found with the provided ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update event."]);
}

?>