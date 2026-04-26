<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para kunin yung current na nakaassign na tourist ng guide
// ginagamot to para ipakita ang active tour details kapag On Tour ang guide
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'No active session found.']); 
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // query para kunin ang tourist data na nakaassign sa current guide.
    // kasama na here yung package at itinerary na gagamitin sa dashboard.
    $query = "SELECT t.customer_id, t.first_name, t.last_name, t.queue_number, t.service_type 
    AS vehicle_type, p.package_name, GROUP_CONCAT(a.attraction_name SEPARATOR ', ') 
    as destinations 
    FROM tour_guides tg 
    JOIN tourists t 
    ON tg.current_tourist_id = t.customer_id 
    LEFT JOIN package_bookings pb 
    ON pb.guide_id = tg.guide_id 
    LEFT JOIN packages p 
    ON pb.package_id = p.package_id 
    LEFT JOIN package_itinerary pi 
    ON p.package_id = pi.package_id 
    LEFT JOIN attractions a 
    ON pi.attraction_id = a.attraction_id 
    WHERE tg.guide_id = ? 
    AND tg.current_status = 'On Tour' 
    GROUP BY t.customer_id";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $tour = mysqli_fetch_assoc($result);

    echo json_encode([
        'success' => true,
        'assigned' => (bool)$tour,
        'data' => $tour
    ]);
} catch (Exception $e) {
    echo json_encode([
    'success' => false,
    'message' => $e->getMessage()]);
}
?>