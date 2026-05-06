<?php

session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__. '/../../../config/config.php';
/** @var mysqli $con */

// Define who is allowed to access this API
$allowed_roles = ['admin', 'tourist'];

// Check if they are logged in AND if their role is in the allowed list
if(!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$fetch_sql = "SELECT attraction_id, attraction_type, attraction_name, address, description, schedule, fee, main_img, mini_one_img, mini_two_img, rec_img FROM attractions ";
$result = mysqli_query($con, $fetch_sql);
    
if ($result) {
    $attractions_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $attractions = array(
            "attraction_id" => $row['attraction_id'],
            "attraction_type" => $row['attraction_type'],
            "attraction_name" => $row['attraction_name'],
            "address" => $row['address'],
            "description" => $row['description'],
            "fee" => $row['fee'],
            "schedule" => $row['schedule'],
            "main_img" => $row['main_img'],
            "mini_one_img" => $row['mini_one_img'],
            "mini_two_img" => $row['mini_two_img'],
            "rec_img" => $row['rec_img']
        );

        array_push($attractions_array, $attractions);
    }

    if (count($attractions_array) > 0) {
        echo json_encode(["status" => "success", "data" => $attractions_array]);
    } else {
        echo json_encode(["status" => "error", "message" => "No attractions found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve attractions."]);
}
?>
