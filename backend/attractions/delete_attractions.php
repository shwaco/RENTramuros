<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Method: DELETE');

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

require_once '../../../asset/config.php';

$data = json_decode(file_get_contents("php://input"));

if(empty($data->attraction_id)) {
    echo json_encode(["status" => "error", "message" => "Missing Attraction ID."]);
    exit();
}

$attraction_id = $data->attraction_id;
$delete_sql = "DELETE FROM Attractions WHERE attraction_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
mysqli_stmt_bind_param($delete_stmt, "i", $attraction_id);
if(mysqli_stmt_execute($delete_stmt)) {
    if(mysqli_stmt_affected_rows($delete_stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Attraction deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No attraction found with the provided ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete attraction."]);
}

?>