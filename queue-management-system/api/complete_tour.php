<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];
$data = json_decode(file_get_contents("php://input"), true);
$customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;

try {
    // inaaupdate yung tour guide as available 
    $updateGuideSql = "UPDATE tour_guides SET current_status = 'Available', current_tourist_id = NULL, became_available_at = NOW() WHERE guide_id = ?";
    $stmtG = mysqli_prepare($con, $updateGuideSql);
    mysqli_stmt_bind_param($stmtG, "i", $guide_id);
    mysqli_stmt_execute($stmtG);

    // then minamark as completed yung tourist and nililink yung guide id ng tourist na nag tour sa kaniya sa db table ng toursits
    if ($customer_id) {
        $updateTouristSql = "UPDATE tourists SET status = 'completed', completed_at = NOW(), guide_id = ? WHERE customer_id = ?";
        $stmtT = mysqli_prepare($con, $updateTouristSql);
        mysqli_stmt_bind_param($stmtT, "ii", $guide_id, $customer_id);
        mysqli_stmt_execute($stmtT);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>