<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para kunin yung completed tour history ng guide —
// yung list ng past tours niya ang isinisend dito para sa history tab ng dashboard
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // tiga-kuha ng lahat ng completed tours ng guide kasama na yung tourist at package details
    $sql = "SELECT 
                t.customer_id,
                t.first_name,
                t.last_name,
                t.email,
                t.phone_number,
                t.service_type AS vehicle_type,
                t.vehicle_count,
                t.adult_count,
                t.children_count,
                t.infant_count,
                t.completed_at,
                t.called_at,
                p.package_name,
                GROUP_CONCAT(DISTINCT a.attraction_name SEPARATOR ', ') AS destinations
            FROM tourists t
            LEFT JOIN packages p ON t.package_id = p.package_id
            LEFT JOIN package_itinerary pi ON p.package_id = pi.package_id
            LEFT JOIN attractions a ON pi.attraction_id = a.attraction_id
            WHERE t.status = 'completed'
                AND t.guide_id = ?
            GROUP BY t.customer_id
            ORDER BY t.completed_at DESC";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $history = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'history' => $history]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>