<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Method: DELETE');

require_once '../../../asset/connect_phpmyadmin.php';

$data = json_decode(file_get_contents("php://input"));

if(empty($data->guide_id)) {
    echo json_encode(["status" => "error", "message" => "Missing Guide ID."]);
    exit();
}

$guide_id = $data->guide_id;

$delete_sql = "DELETE FROM tour_guides WHERE guide_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
mysqli_stmt_bind_param($delete_stmt, "i", $guide_id);

if(mysqli_stmt_execute($delete_stmt)) {
    if(mysqli_stmt_affected_rows($delete_stmt) > 0) {
        echo json_encode(["status" => "success", "message" => "Tour guide deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No tour guide found with the provided ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete tour guide."]);
}

?>