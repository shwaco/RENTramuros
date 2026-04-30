<?php
// session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../../asset/config.php';
/** @var mysqli $con */

// if($_SESSION['admin_id'] ?? null) {
//     // Admin is logged in, proceed with the request
// } else {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in as admin."]);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));
if (empty($data->event_name) || empty($data->description) || empty($data->schedule) || empty($data->image_file)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$event_name = $data->event_name;
$description = $data->description;
$schedule = $data->schedule;
$image_file = $data->image_file;
$insert_sql = "INSERT INTO upcoming_events (event_name, description, schedule, image_file) VALUES (?, ?, ?, ?)";
$insert_stmt = mysqli_prepare($con, $insert_sql);
mysqli_stmt_bind_param($insert_stmt, "ssss", $event_name, $description, $schedule, $image_file);
if (mysqli_stmt_execute($insert_stmt)) {
    echo json_encode(["status" => "success", "message" => "Event added successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add event."]);
}

?>