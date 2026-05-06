<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__. '/../../../config/config.php';
/** @var mysqli $con */

if ($_SESSION['admin_id'] ?? null) {
    // Tourist or admin is logged in, proceed with the request
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "Error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$sql = "SELECT guide_id, first_name, last_name, email, current_status, last_active_time, last_dispatch_time, became_available_at, current_tourist_id FROM tour_guides ORDER BY guide_id";
$result = mysqli_query($con, $sql);

if ($result) {
    $guides_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $guides_array = array(
            "guide_id" => $row['guide_id'],
            "first_name" => $row['first_name'],
            "last_name" => $row['last_name'],
            "email" => $row['email'],
            "current_status" => $row['current_status'],
            "last_active_time" => $row['last_active_time'],
            "last_dispatch_time" => $row['last_dispatch_time'],
            "became_available_at" => $row['became_available_at'],
            "current_tourist_id" => $row['current_tourist_id']
        );
    }

    echo json_encode(["status" => "Success", "data" => $guides_array]);
} else {
    echo json_encode(["status" => "Error", "message" => "Failed to retrieve tour guide data."]);
}
?>