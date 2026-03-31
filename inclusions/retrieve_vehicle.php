<?php

header('Content-Type: application/json');
require_once '../queue-management-system/config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->query("
        SELECT vehicle_id, vehicle_type, passenger_capacity, current_status, last_dispatch_time
        FROM vehicles 
        ORDER BY vehicle_type ASC
    ");
    
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'data' => $vehicles
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>