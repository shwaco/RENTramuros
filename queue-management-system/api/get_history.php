<?php
header('Content-Type: application/json');
require_once '../config.php'; 

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];
$db = new Database();
$conn = $db->getConnection();

try {
    // We select the last 10 completed tours specifically for THIS guide
    $stmt = $conn->prepare("
        SELECT queue_number, first_name, last_name, service_type AS vehicle_type, completed_at 
        FROM tourists 
        WHERE guide_id = ? AND status = 'completed' 
        ORDER BY completed_at DESC 
        LIMIT 10
    ");
    $stmt->execute([$guide_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'history' => $history]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>