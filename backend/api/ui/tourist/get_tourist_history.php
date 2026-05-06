<?php
session_start();
header('Content-Type: application/json');

// Siguraduhing tama ang path papunta sa config.php ninyo
require_once '../../../config/config.php'; 

// 1. I-check kung may naka-login na tourist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tourist') {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit();
}

$tourist_id = $_SESSION['user_id'];

// 2. I-query ang database para sa mga bookings ng tourist na ito
// Kinopya natin ang structure mula sa Tour Guide dashboard mo para tugma sa resibo
$query = "SELECT 
            bh.booking_request_id,
            bh.unique_id,
            bh.booking_date,
            bh.booking_time,
            bh.adults_and_seniors,
            bh.children,
            bh.infants,
            bh.number_of_vehicle,
            bh.status,
            v.vehicle_type,
            v.price AS vehicle_price,
            ci.first_name,
            ci.last_name,
            ci.email_address,
            ci.phone_number,
            p.package_name,
            p.price AS package_price,
            GROUP_CONCAT(
                DISTINCT CONCAT(a.attraction_name, '|', IFNULL(a.fee, 0))
                ORDER BY a.attraction_name
                SEPARATOR ','
            ) AS destinations
          FROM booking_history bh
          LEFT JOIN contact_information ci  ON bh.contact_info_id = ci.contact_info_id
          LEFT JOIN vehicles v              ON bh.vehicle_id = v.vehicle_id
          LEFT JOIN packages p              ON bh.package_id = p.package_id
          LEFT JOIN request_attractions ra  ON bh.booking_request_id = ra.booking_request_id
          LEFT JOIN package_itinerary pi    ON bh.package_id = pi.package_id
          LEFT JOIN attractions a           ON (ra.attraction_id = a.attraction_id OR pi.attraction_id = a.attraction_id)
          WHERE bh.tourist_id = ? 
          GROUP BY bh.booking_request_id
          ORDER BY bh.booking_date DESC";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $tourist_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$history = [];
while ($row = mysqli_fetch_assoc($result)) {
    $history[] = $row;
}

// 3. I-return bilang JSON pabalik sa dynamic_landing.js
echo json_encode([
    "success" => true,
    "history" => $history
]);
?>