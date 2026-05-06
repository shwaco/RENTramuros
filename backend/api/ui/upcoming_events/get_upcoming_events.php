<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__. '/../../../config/config.php';
/** @var mysqli $con */

// Define who is allowed to access this API
// $allowed_roles = ['admin', 'tourist'];

// Check if they are logged in AND if their role is in the allowed list
// if(!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowed_roles)) {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
//     exit();
// }

$fetch_sql = "SELECT event_id, event_name, event_date, event_time, location, image_file FROM upcoming_events ORDER BY event_date ASC, event_time ASC";
$fetch_stmt = mysqli_prepare($con, $fetch_sql);
if(mysqli_stmt_execute($fetch_stmt)) {
    $result = mysqli_stmt_get_result($fetch_stmt);
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }

    if (count($events) > 0) {
        echo json_encode(["status" => "success", "data" => $events]);
    } else {
        echo json_encode(["status" => "error", "message" => "No upcoming events found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve upcoming events."]);
}

?>