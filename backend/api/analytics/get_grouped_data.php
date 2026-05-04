<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

session_start();

$logged_in_tourist_id = $_SESSION['tourist_id'] ?? null;
$fetch_tourist_id = "SELECT *FROM booking_history WHERE tourist_id = ?";

require_once '../asset/config.php';
/** @var mysqli $con */
// require_once 'grouping_logic.php';

if($_SESSION['tourist_id'] ?? null) {
    // Tourist or admin is logged in, proceed with the request
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
    exit();
}

// $tourist_id = $_SESSION['tourist_id'];

if (empty($_GET['tourist_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
}

$tourist_id = $_GET['tourist_id'];
$booking_type = $_GET['booking_type'] ?? null;
// $attractions = getGroupedAttractions($con, $booking_request_id);
// $attractions = $data->attraction_id ?? null;
try{
    if ($booking_type == "Attractions") {
        $fetch_sql = "SELECT bh.tourist_id, bh.booking_request_id, bh.adults_and_seniors, bh.children, bh.infants, bh.booking_type, GROUP_CONCAT(ra.attraction_id SEPARATOR ', ') AS attraction_ids, v.vehicle_type, bh.number_of_vehicle, ci.last_name, ci.first_name, ci.email_address, ci.phone_number
                      FROM booking_history AS bh
                      LEFT JOIN request_attractions AS ra ON bh.booking_request_id = ra.booking_request_id
                      LEFT JOIN contact_information AS ci ON ci.contact_info_id = bh.contact_info_id
                      LEFT JOIN vehicles AS v ON v.vehicle_id = bh.vehicle_id
                      WHERE bh.tourist_id = ? AND bh.booking_type = 'Attractions'
                      GROUP BY bh.booking_request_id
                      ORDER BY bh.booking_request_id ASC";
                      // HAVING t.tourist_id = ?";
        $fetch_stmt = mysqli_prepare($con, $fetch_sql);
        mysqli_stmt_bind_param($fetch_stmt, "i", $tourist_id);
    
        if(mysqli_stmt_execute($fetch_stmt)) {
            $result = mysqli_stmt_get_result($fetch_stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($data, $row);
            }
            http_response_code(200);
            echo json_encode(["status" => "success", "data" => $data]);
            exit();
        }
    
    }else if ($booking_type == "Packages") {
        $fetch_sql =" SELECT bh.tourist_id, bh.booking_request_id, bh.adults_and_seniors, bh.children, bh.infants, bh.booking_type, p.package_name, GROUP_CONCAT(pi.attraction_id SEPARATOR ', ') AS itinerary, v.vehicle_type, bh.number_of_vehicle, ci.last_name, ci.first_name, ci.email_address, ci.phone_number
                      FROM booking_history AS bh
                      LEFT JOIN request_packages AS rp ON bh.booking_request_id = rp.booking_request_id
                      LEFT JOIN packages AS p ON rp.package_id = p.package_id
                      LEFT JOIN package_itinerary AS pi ON p.package_id = pi.package_id
                      LEFT JOIN contact_information AS ci ON ci.contact_info_id = bh.contact_info_id
                      LEFT JOIN vehicles AS v ON v.vehicle_id = bh.vehicle_id
                      WHERE bh.tourist_id = ? AND bh.booking_type = 'Packages'
                      GROUP BY bh.booking_request_id
                      ORDER BY bh.booking_request_id ASC";
    
        $fetch_stmt = mysqli_prepare($con, $fetch_sql);
        mysqli_stmt_bind_param($fetch_stmt, "i", $tourist_id);

        if(mysqli_stmt_execute($fetch_stmt)) {
            $result = mysqli_stmt_get_result($fetch_stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($data, $row);
            }
            http_response_code(200);
            echo json_encode(["status" => "success", "data" => $data]);
            exit();
        }
    
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid booking type."]);
        exit();
    }

} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>