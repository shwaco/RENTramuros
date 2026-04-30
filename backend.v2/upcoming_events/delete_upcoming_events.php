<?php
// session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');

require_once '../asset/config.php';
/** @var mysqli $con */

// if ($_SESSION['admin_id'] ?? null) {
//     // Admin is logged in, proceed with the request
// } else {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in as admin."]);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->event_id)) {
    echo json_encode(["status" => "error", "message" => "Missing Event ID."]);
    exit();
}

$event_id = $data->event_id;
$delete_sql = "DELETE FROM upcoming_events WHERE event_id = ?";
$delete_stmt = mysqli_prepare($con, $delete_sql);
mysqli_stmt_bind_param($delete_stmt, "i", $event_id);

if (mysqli_stmt_execute($delete_stmt)) {
    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Event deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No event found with the provided ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete event."]);
}

?>