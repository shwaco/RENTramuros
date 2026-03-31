<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $stats = [];
    
    // kinacount yung mga naghihintay na tourist
    $stmt = $conn->query("SELECT COUNT(*) as count FROM tourists WHERE status = 'waiting' AND DATE(created_at) = CURDATE()");
    $stats['waiting'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // kinacount yung mga ongoing na tours
    $stmt = $conn->query("SELECT COUNT(*) as count FROM tourists WHERE status = 'serving' AND DATE(created_at) = CURDATE()");
    $stats['serving'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // kinacount yng mga nacomplete na tours
    $stmt = $conn->query("SELECT COUNT(*) as count FROM tourists WHERE status = 'completed' AND DATE(created_at) = CURDATE()");
    $stats['completed'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // kinacaount kung ilan yung tourists
    $stmt = $conn->query("SELECT COUNT(*) as count FROM tourists WHERE DATE(created_at) = CURDATE()");
    $stats['today_total'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo json_encode([
        'success' => true,
        'data' => $stats
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>