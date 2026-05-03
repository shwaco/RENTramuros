<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once '../../../config/config.php';
/** @var mysqli $con */

if(!isset($_SESSION['tourist_id']) && !isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use GET."]);
    exit();
}

$fetch_sql = "SELECT p.package_id, p.package_name, p.description, p.price, p.image_file , GROUP_CONCAT(pi.attraction_id SEPARATOR ', ') AS itinerary
              FROM packages AS p
              LEFT JOIN package_itinerary AS pi ON p.package_id = pi.package_id
              GROUP BY p.package_id
              ";
$result = mysqli_query($con, $fetch_sql);

if ($result) {
    $packages_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $packages = array(
        	"package_id" => $row['package_id'],
            "package_name" => $row['package_name'],
            "description" => $row['description'],
            "price" => $row['price'],
            "image_file" => $row['image_file'],
            "itinerary" => $row['itinerary']
        );

        array_push($packages_array, $packages);
    }
    if (count($packages_array) > 0) {
    echo json_encode(["status" => "success", "data" => $packages_array]);
    } else {
    echo json_encode(["status" => "error", "message" => "No packages found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve packages."]);
}

?>