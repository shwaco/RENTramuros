<?php

use Dom\Mysql;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if(!isset($data->tourist_id) || !isset($data->booking_type)){
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing Tourist ID or Booking Type"]);
    exit();
}

include_once('connect_phpmyadmin.php');

$data = json_decode(file_get_contents("php://input"), true);
$tourist_id = $data->tourist_id;
$booking_type = $data->booking_type;

mysqli_begin_transaction($con);

try {
    $reservation_sql = "INSERT INTO Reservations (tourist_id, booking_date, status, booking_type) VALUES (?, NOW(), 'pending', ?)";
    $stmt = mysqli_prepare($con, $reservation_sql);
    mysqli_stmt_bind_param($stmt, "is", $tourist_id, $booking_type);
    mysqli_stmt_execute($stmt);
    $reservation_id = mysqli_insert_id($con);

    if ($booking_type === 'Package') {
        $package_id = $data->package_id;
        $tour_date = $data->tour_date;
        $passenger_count = $data->passenger_count;

        $vehicle_sql = "SELECT vehicle_id FROM Vehicles WHERE current_status = 'available' AND passenger_capacity >= ? ORDER BY passenger_capacity ASC LIMIT 1 FOR UPDATE";
        $vehicle_stmt = mysqli_prepare($con, $vehicle_sql);
        mysqli_stmt_bind_param($vehicle_stmt, "i", $passenger_count);
        mysqli_stmt_execute($vehicle_stmt);
        $vehicle_result = mysqli_stmt_get_result($vehicle_stmt);
        
        if (mysqli_num_rows($vehicle_result) == 0) throw new Exception("No available vehicles for the specified passenger count.");
        $assigned_vehicle_id = mysqli_fetch_assoc($vehicle_result)['vehicle_id'];
        

        $guide_sql = "SELECT guide_id FROM Tour_Guides WHERE current_status = 'available' ORDER BY last_dispatch_time ASC LIMIT 1 FOR UPDATE"; 
        $guide_result = mysqli_query($con, $guide_sql);

        if (mysqli_num_rows($guide_result) == 0) throw new Exception("No available tour guides at the moment.");
        $assigned_guide_id = mysqli_fetch_assoc($guide_result)['guide_id'];

        $package_sql = "INSERT INTO package_bookings (reservation_id, package_id, vehicle_id, guide_id, tour_date, passenger_count) VALUES (?, ?, ?, ?, ?, ?)";
        $package_stmt = mysqli_prepare($con, $package_sql);
        mysqli_stmt_bind_param($package_stmt, "iiiiii", $reservation_id, $package_id, $vehicle_id, $guide_id, $tour_date, $passenger_count);
        mysqli_stmt_execute($package_stmt);

        mysqli_query($con, "UPDATE Vehicles SET current_status = 'unavailable' WHERE vehicle_id = $assigned_vehicle_id");
        mysqli_query($con, "UPDATE Tour_Guides SET current_status = 'unavailable', last_dispatch_time = NOW() WHERE guide_id = $assigned_guide_id");

    }elseif ($booking_type === 'Attractions') {
        $attraction_id = $data->attraction_id;
        $visit_date = $data->visit_date;
        $ticket_quantity = $data->ticket_quantity;

        $attraction_sql = "INSERT INTO attraction_bookings (reservation_id, attraction_id, visit_date, ticket_quantity) VALUES (?, ?, ?, ?)";
        $attraction_stmt = mysqli_prepare($con, $attraction_sql);
        mysqli_stmt_bind_param($attraction_stmt, "iiis", $reservation_id, $attraction_id, $visit_date, $ticket_quantity);
        mysqli_stmt_execute($attraction_stmt);
        
    } else {
        throw new Exception("Invalid booking type specified.");
    }

    mysqli_commit($con);
    http_response_code(201);

    echo json_encode(["status" => "success", "message" => "Booking created successfully", "reservation_id" => $reservation_id]);
} catch (Exception $e) {
    mysqli_rollback($con);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>