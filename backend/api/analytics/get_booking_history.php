<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

// Adjust this path if your config file is located somewhere else based on your tree!
require_once '../../config/config.php';
/** @var mysqli $con */

// Security Check (Uncomment when ready)
// /*
// Inside get_guide.php

// Change 'admin_id' to 'user_id' AND make sure the role is 'admin'
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
    exit();
}
// */

// Using LEFT JOIN ensures bookings without guides/vehicles still appear!
$sql = "SELECT 
            bh.unique_id, 
            bh.booking_type, 
            bh.status, 
            bh.booking_date, 
            bh.booking_time, 
            bh.adults_and_seniors, 
            bh.children, 
            bh.infants,
            ci.first_name AS customer_fname, 
            ci.last_name AS customer_lname, 
            ci.email_address AS customer_email, 
            ci.phone_number AS customer_phone,
            v.vehicle_type, 
            v.passenger_capacity,
            tg.first_name AS guide_fname, 
            tg.last_name AS guide_lname, 
            tg.email AS guide_email
        FROM booking_history bh
        LEFT JOIN contact_information ci ON bh.contact_info_id = ci.contact_info_id
        LEFT JOIN vehicles v ON bh.vehicle_id = v.vehicle_id
        LEFT JOIN tour_guides tg ON bh.guide_id = tg.guide_id
        ORDER BY bh.booking_request_id DESC 
        LIMIT 20"; // Adjust limit as needed

$result = mysqli_query($con, $sql);

if($result) {
    $bookings = [];
    while($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $bookings]);
} else {
    echo json_encode(["status" => "error", "message" => "Database query failed: " . mysqli_error($con)]);
}
?>