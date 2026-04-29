<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once '../asset/config.php';
/** @var mysqli $con */

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$fetch_sql = "SELECT tourist_id, first_name, last_name, email, phone_number FROM tourists ORDER BY tourist_id ASC";
$fetch_stmt = mysqli_prepare($con, $fetch_sql);

if (mysqli_stmt_execute($fetch_stmt)) {
    $result = mysqli_stmt_get_result($fetch_stmt);
    $tourists_array = [];

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($tourists_array, $row);
    }

    if (count($tourists_array) > 0) {
        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $tourists_array]);
    } else {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "No tourists found in the database.", "data" => []]);
    }
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to retrieve the list of tourists."]);
}
?>