<?php
header('Content-Type: application/json');

require_once '../queue-management-system/config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->query("
        SELECT guide_id, first_name, last_name, current_status, last_dispatch_time, became_available_at
        FROM tour_guides 
        ORDER BY current_status ASC, first_name ASC
    ");
    
    $guides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'data' => $guides
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>