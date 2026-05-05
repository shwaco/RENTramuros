<?php
session_start();
header('Content-Type: application/json');
require_once('../../../config/config.php');

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'No active session found.']);
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    /* Mas matibay na query gamit ang subquery para sa destinations */
  $query = "SELECT 
            bh.booking_request_id,
            bh.unique_id,
            bh.booking_date,
            bh.adults_and_seniors,
            bh.children,
            bh.infants,
            bh.number_of_vehicle,
            bh.package_id, /* Importante ito para sa subquery[cite: 27] */
            v.vehicle_type,
            v.price AS vehicle_price,
            ci.first_name,
            ci.last_name,
            ci.email_address,
            ci.phone_number,
            p.package_name,
            p.price AS package_price,
            (SELECT GROUP_CONCAT(CONCAT(a.attraction_name, '|', IFNULL(a.fee, 0)) ORDER BY a.attraction_name SEPARATOR ',')
             FROM attractions a
             WHERE a.attraction_id IN (
                 SELECT ra.attraction_id FROM request_attractions ra WHERE ra.booking_request_id = bh.booking_request_id
                 UNION
                 SELECT pi.attraction_id FROM package_itinerary pi WHERE pi.package_id = bh.package_id
             )
            ) AS destinations
          FROM booking_history bh
          LEFT JOIN contact_information ci  ON bh.contact_info_id   = ci.contact_info_id
          LEFT JOIN vehicles v              ON bh.vehicle_id         = v.vehicle_id
          LEFT JOIN packages p              ON bh.package_id         = p.package_id
          WHERE bh.guide_id = ?
            AND bh.status = 'Accepted'
          GROUP BY bh.booking_request_id
          LIMIT 1";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tour   = mysqli_fetch_assoc($result);

    echo json_encode([
        'success'  => true,
        'assigned' => (bool)$tour,
        'data'     => $tour
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>