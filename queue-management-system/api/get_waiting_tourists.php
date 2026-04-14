<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para kunin yung list ng mga waitng tourists
// ginagamit to ng queue lobby sa frontend para igenerate yung mga tourist blocks
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

try {
    $sql = "SELECT t.customer_id, t.first_name, t.last_name, t.email, t.phone_number, 
                   t.service_type, t.called_at, t.created_at, t.adult_count, 
                   t.children_count, t.infant_count, t.vehicle_count, 
                   t.destinations, p.package_name 
            FROM tourists t 
            LEFT JOIN packages p ON t.package_id = p.package_id
            WHERE t.status IN ('active', 'waiting') 
            AND t.guide_id IS NULL 
            ORDER BY t.customer_id ASC";
    
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