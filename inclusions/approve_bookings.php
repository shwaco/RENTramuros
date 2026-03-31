<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once('../asset/connect_phpmyadmin.php');

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->reservation_id)) {
    echo json_encode(["status" => "error", "message" => "Missing reservation_id."]);
    exit();
} 

$reservation_id = $data->reservation_id;
$sql = "UPDATE RESERVATIONS
        SET status = 'Approve'
        WHERE reservation_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);

if(mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "Booking of #" . $reservation_id . " Approved Successfully!"]);
    
} else {
    echo json_encode(["status" => "error", "message" => "Error Approving Booking."]);
}
?>