<?php
header('Content-Type: application/json');
require_once '../config.php'; 

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];
$data = json_decode(file_get_contents("php://input"), true);
$customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;

try {
    $db = new Database();
    $conn = $db->getConnection();

    // inaaupdate yung tour guide as available 
    $stmtG = $conn->prepare("UPDATE tour_guides SET current_status = 'Available', current_tourist_id = NULL, became_available_at = NOW() WHERE guide_id = ?");
    $stmtG->execute([$guide_id]);

    // then minamark as completed yung tourist and nililink yung guide id ng tourist na nag tour sa kaniya sa db table ng toursits
    if ($customer_id) {
        $stmtT = $conn->prepare("UPDATE tourists SET status = 'completed', completed_at = NOW(), guide_id = ? WHERE customer_id = ?");
        $stmtT->execute([$guide_id, $customer_id]);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>