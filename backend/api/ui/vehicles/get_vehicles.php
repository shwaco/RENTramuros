<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once '../../../config/config.php';
/** @var mysqli $con */
//if(!isset($_SESSION['tourist_id']) && !isset($_SESSION['admin_id'])) {
//    http_response_code(401);
//    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
//    exit();
//}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$sql = "SELECT vehicle_id, vehicle_type, passenger_capacity, price, image_file FROM vehicles";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    $vehicles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $vehicles[] = array(
            "vehicle_id" => $row['vehicle_id'],
            "vehicle_type" => $row['vehicle_type'],
            "passenger_capacity" => $row['passenger_capacity'],
            "price" => $row['price'],
            "image_file" => $row['image_file']
        );
    }
    echo json_encode(["status" => "success", "data" => $vehicles]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}



?>