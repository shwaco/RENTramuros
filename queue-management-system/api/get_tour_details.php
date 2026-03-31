<?php
header('Content-Type: application/json');
require_once '../config.php';

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false]); exit();
}

$guide_id = $_SESSION['guide_id'];
$db = new Database();
$conn = $db->getConnection();

try {
    // Simplified query using the tourists table you actually have
    $query = "SELECT t.customer_id, t.first_name, t.last_name, t.queue_number, 
              t.service_type AS vehicle_type, p.package_name,
              GROUP_CONCAT(a.attraction_name SEPARATOR ', ') as destinations
              FROM tour_guides tg
              JOIN tourists t ON tg.current_tourist_id = t.customer_id
              LEFT JOIN package_bookings pb ON pb.guide_id = tg.guide_id
              LEFT JOIN packages p ON pb.package_id = p.package_id
              LEFT JOIN package_itinerary pi ON p.package_id = pi.package_id
              LEFT JOIN attractions a ON pi.attraction_id = a.attraction_id
              WHERE tg.guide_id = ? AND tg.current_status = 'Busy'
              GROUP BY t.customer_id";

    $stmt = $conn->prepare($query);
    $stmt->execute([$guide_id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'assigned' => (bool)$tour,
        'data' => $tour
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>