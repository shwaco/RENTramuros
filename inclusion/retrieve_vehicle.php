<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

require_once '../asset/connect_phpmyadmin.php';

$sql = "SELECT vehicle_id, vehicle_type, passenger_capacity, current_status, last_dispatch_time FROM Vehicles ORDER BY vehicle_id";
$result = mysqli_query($con, $sql);

if ($result) {
    $vehicles = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $vehicles[] = $row;
    }

    echo json_encode(["status" => "Success", "data" => $vehicles]);
} else {
    echo json_encode(["status" => "Error", "message" => "Failed to retrieve vehicle data."]);
}
?>