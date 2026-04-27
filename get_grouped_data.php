<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

// session_start();
// 
// $logged_in_tourist_id = $_SESSION['tourist_id'] ?? null;
// $fetch_tourist_id = "SELECT *FROM booking_history WHERE tourist_id = ?";

require_once 'asset/config.php';
require_once 'grouping_logic.php';

if(empty($_GET['booking_request_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$booking_request_id = $_GET['booking_request_id'];
$attractions = getGroupedAttractions($con, $booking_request_id);

$fetch_sql = "SELECT bh.adults_and_seniors, bh.children, bh.infants, bh.booking_type, GROUP_CONCAT(a.attraction_name SEPARATOR ', '), v.vehicle_type, bh.number_of_vehicle, ci.last_name, ci.first_name, ci.email_address, ci.phone_number
              FROM attractions AS a
              CROSS JOIN request_attractions AS ra ON a.attraction_id = ra.attraction_id
              CROSS JOIN booking_history AS bh ON ra.booking_request_id = bh.booking_request_id
              CROSS JOIN contact_information AS ci ON ci.contact_info_id = bh.contact_info_id
              CROSS JOIN vehicles AS v ON v.vehicle_id = bh.vehicle_id
              WHERE ra.booking_request_id = ?";

$fetch_stmt = mysqli_prepare($con, $fetch_sql);
mysqli_stmt_bind_param($fetch_stmt, "i", $booking_request_id);

if(mysqli_stmt_execute($fetch_stmt)) {
    $result = mysqli_stmt_get_result($fetch_stmt);
    $grouped_data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($grouped_data, $row);
    }
    echo json_encode(["status" => "success", "data" => $grouped_data]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to fetch grouped data."]);
}

mysqli_stmt_close($fetch_stmt);
?>