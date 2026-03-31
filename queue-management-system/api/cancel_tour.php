<?php
// FOR NOW Wala PA TONG PURPOSE 
session_start();
header('Content-Type: application/json');
require_once '../config.php';

$data = json_decode(file_get_contents('php://input'), true);
$touristId = $data['customer_id'] ?? null;
$guideId = $_SESSION['guide_id'] ?? 1;

if (!$touristId) {
    echo json_encode(['success' => false, 'message' => 'Missing tourist ID']);
    exit;
}

$db = new Database();
$conn = $db->getConnection();

try {
    $conn->beginTransaction();

    $conn->prepare("UPDATE tourists SET status = 'cancelled' WHERE customer_id = ?")
         ->execute([$touristId]);

    $conn->prepare("UPDATE tour_guides SET current_status = 'Available', current_tourist_id = NULL, became_available_at = NOW() WHERE guide_id = ?")
         ->execute([$guideId]);

    require_once 'dispatch.php';
    runDispatch($conn);

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Tour Cancelled']);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>