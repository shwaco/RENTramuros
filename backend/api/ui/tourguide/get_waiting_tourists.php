<?php
session_start();
header('Content-Type: application/json');
require_once('../../../config/config.php');

// GET endpoint — tinatawag ng initWaitingLobby() sa lobby.js
// nagre-return ng JSON array ng lahat ng 'Pending' bookings na wala pang assigned na guide
// Ni limit ko muna siya as 20 pinaka-recent ang nasa itaas via ORDER BY booking_request_id ASC

// Returns all Pending bookings with no assigned guide.
// Field names are aliased to match the existing JS (customer_id, adult_count, etc.)
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

try {
    // FLOW: Multi-table join ulit. Parehas lang din yung logic sa destinations para makita agad ng guide kung anong package or custom destinations yung pinili ng naghihintay na tourist
    $sql = "SELECT 
                bh.booking_request_id,
                bh.booking_date,
                bh.booking_time,
                bh.adults_and_seniors,
                bh.children,
                bh.infants,
                bh.number_of_vehicle,
                bh.status,
                bh.booking_type,
                v.vehicle_type,
                v.price AS vehicle_price,
                ci.first_name,
                ci.last_name,
                ci.email_address,
                ci.phone_number,
                p.package_name,
                p.price AS package_price,
                GROUP_CONCAT(
                    CONCAT(a.attraction_name, '|', IFNULL(a.fee, 0))
                    ORDER BY a.attraction_name
                    SEPARATOR ','
                )                                                   AS destinations
            FROM booking_history bh
            LEFT JOIN contact_information ci  ON bh.contact_info_id  = ci.contact_info_id
            LEFT JOIN vehicles v              ON bh.vehicle_id        = v.vehicle_id
            LEFT JOIN packages p              ON bh.package_id        = p.package_id
            LEFT JOIN request_attractions ra  ON bh.booking_request_id = ra.booking_request_id
            LEFT JOIN package_itinerary pi    ON bh.package_id         = pi.package_id
            LEFT JOIN attractions a           ON a.attraction_id = ra.attraction_id OR a.attraction_id = pi.attraction_id
            WHERE bh.status = 'Pending'
              AND bh.guide_id IS NULL
            GROUP BY bh.booking_request_id
            ORDER BY bh.booking_request_id ASC
            LIMIT 20";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($con));
    }

    $tourists = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'data' => $tourists]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>