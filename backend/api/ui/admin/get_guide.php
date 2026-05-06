<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__. '/../../../config/config.php';
/** @var mysqli $con */

// Change 'admin_id' to 'user_id' AND make sure the role is 'admin'
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "Error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$sql = "SELECT guide_id, first_name, last_name, email, current_status, last_active_at, last_dispatch_time, became_available_at, current_tourist_id FROM tour_guides ORDER BY guide_id";
$result = mysqli_query($con, $sql);

if ($result) {
    $guides = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $guides[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $guides]);
} else {
    echo json_encode(["status" => "Error", "message" => "Failed to retrieve tour guide data."]);
}
?>