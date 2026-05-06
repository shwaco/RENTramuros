<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__. '/../../config/config.php'; // Adjust your ../ path depending on where you save this!
/** @var mysqli $con */

try {
    // We will build an array to hold our 4 numbers
    $stats = [
        "pending" => 0,
        "accepted" => 0,
        "on_tour" => 0,
        "completed" => 0
    ];

    // 1. Count Pending Bookings
    $res = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE status = 'Pending'");
    if($row = mysqli_fetch_assoc($res)) $stats['pending'] = $row['count'];

    // 2. Count Accepted Bookings
    $res = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE status = 'Accepted'");
    if($row = mysqli_fetch_assoc($res)) $stats['accepted'] = $row['count'];

    // 3. Count Active Tours
    $res = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE status = 'On Tour'");
    if($row = mysqli_fetch_assoc($res)) $stats['on_tour'] = $row['count'];

    // 4. Count Completed Tours
    $res = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE status = 'Completed'");
    if($row = mysqli_fetch_assoc($res)) $stats['completed'] = $row['count'];

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $stats]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to fetch dashboard stats."]);
}
?>