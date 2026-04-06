<?php
// FOR NOW Wala PA TONG PURPOSE 
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

$data = json_decode(file_get_contents('php://input'), true);
$touristId = $data['customer_id'] ?? null;
$guideId = $_SESSION['guide_id'] ?? 1;

if (!$touristId) {
    echo json_encode(['success' => false, 'message' => 'Missing tourist ID']);
    exit;
}

try {
    mysqli_begin_transaction($con);

    $cancelTouristSql = "UPDATE tourists SET status = 'cancelled' WHERE customer_id = ?";
    $stmtT = mysqli_prepare($con, $cancelTouristSql);
    mysqli_stmt_bind_param($stmtT, "i", $touristId);
    mysqli_stmt_execute($stmtT);

    $updateGuideSql = "UPDATE tour_guides SET current_status = 'Available', current_tourist_id = NULL, became_available_at = NOW() WHERE guide_id = ?";
    $stmtG = mysqli_prepare($con, $updateGuideSql);
    mysqli_stmt_bind_param($stmtG, "i", $guideId);
    mysqli_stmt_execute($stmtG);

    require_once 'dispatch.php';
    runDispatch($conn);

    mysqli_commit($con);
    echo json_encode(['success' => true, 'message' => 'Tour Cancelled']);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>