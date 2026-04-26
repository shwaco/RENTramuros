<?php

// session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once '../../asset/config.php';

// if ($_GET['admin'] ?? null) {
//     if ($_SESSION['admin_id'] ?? null) {
//         // Admin is logged in, proceed with the request
//     } else {
//         http_response_code(401);
//         echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in as admin."]);
//         exit();
//     }
// }

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$fetch_sql = "SELECT attraction_id, attraction_type, attraction_name, description, schedule, fee, main_img, mini_one_img, mini_two_img, rec_img FROM attractions ";
$result = mysqli_query($con, $fetch_sql);
    
if ($result) {
    $attractions_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $attractions = array(
            "attraction_id" => $row['attraction_id'],
            "attraction_type" => $row['attraction_type'],
            "attraction_name" => $row['attraction_name'],
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