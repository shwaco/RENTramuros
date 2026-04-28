<?php
// session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once 'asset/config.php';

// if ($_SESSION['tourist_id'] ?? null || $_SESSION['admin_id'] ?? null) {
//     // Tourist or admin is logged in, proceed with the request
// } else {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
//     exit();
// }

if(empty($_GET['booking_request_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}


$booking_request_id = $_GET['booking_request_id'];

$fetch_sql = "SELECT attraction_id FROM request_attractions WHERE booking_request_id = ?";
$fetch_stmt = mysqli_prepare($con, $fetch_sql);
mysqli_stmt_bind_param($fetch_stmt, "i", $booking_request_id);

if(mysqli_stmt_execute($fetch_stmt)) {
    $result = mysqli_stmt_get_result($fetch_stmt);
    $attractions_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $attractions = array(
            "attraction_id" => $row['attraction_id']
        );

        array_push($attractions_array, $attractions);
    }

    if (count($attractions_array) > 0) {
        echo json_encode(["status" => "success", "data" => $attractions_array]);
    } else {
        echo json_encode(["status" => "error", "message" => "No attractions found for this booking request."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve attractions for the booking request."]);
}

?>