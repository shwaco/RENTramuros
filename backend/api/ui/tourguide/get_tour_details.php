<?php
session_start();
header('Content-Type: application/json');
require_once('../../../config/config.php');

// GET endpoint — nagre-return ng JSON ng currently 'Accepted' booking ng guide
// using multi-table LEFT JOIN + GROUP_CONCAT — same structure ng get_history.php
// tinatawag kapag kailangan ng JS na i-fetch ang live tour data ng guide

// Returns the currently Accepted booking assigned to this guide.
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'No active session found.']);
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // FLOW: Pareho lang to halos ng get_history.php, pero naka-filter (WHERE) na dapat 'Accepted' 
    // ang status at limit to 1 kasi isa lang naman ang pwedeng i-tour ng guide at a time
    $query = "SELECT
                bh.booking_request_id,
                bh.booking_date,
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
                /* Pinagsasama lahat ng attractions (either attractions lang or package) sa iisang string */
                GROUP_CONCAT(
                    CONCAT(a.attraction_name, '|', IFNULL(a.fee, 0))
                    ORDER BY a.attraction_name
                    SEPARATOR ','
                ) AS destinations
            FROM booking_history bh
            LEFT JOIN contact_information ci  ON bh.contact_info_id   = ci.contact_info_id
            LEFT JOIN vehicles v              ON bh.vehicle_id         = v.vehicle_id
            LEFT JOIN packages p              ON bh.package_id         = p.package_id
            LEFT JOIN request_attractions ra  ON bh.booking_request_id = ra.booking_request_id
            LEFT JOIN package_itinerary pi    ON bh.package_id         = pi.package_id
            LEFT JOIN attractions a           ON a.attraction_id = ra.attraction_id OR a.attraction_id = pi.attraction_id
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