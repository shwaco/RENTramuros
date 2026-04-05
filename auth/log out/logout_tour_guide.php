<?php
session_start();
header('Content-Type: application/json');
require_once '../../config.php'; 

if (isset($_SESSION['guide_id'])) {
    $guide_id = $_SESSION['guide_id'];
    $db = new Database();
    $conn = $db->getConnection();

    try {
        // Mark as offline to remove them from the active queue
        $stmt = $conn->prepare("UPDATE tour_guides SET current_status = 'Offline', current_tourist_id = NULL WHERE guide_id = ?");
        $stmt->execute([$guide_id]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
} 

session_unset();
session_destroy();

echo json_encode(['success' => true, 'message' => 'Tour guide logged out successfully']);
?>