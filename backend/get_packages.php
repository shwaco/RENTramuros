<?php
// session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once '../config/config.php';

// if(!isset($_SESSION['tourist_id']) && !isset($_SESSION['admin_id'])) {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$sql = "SELECT * FROM packages";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    $packages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $packages[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $packages]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}



?>