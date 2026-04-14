<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para imark ang current tour bilang completed
// nirereset nito ang guide state pabalik sa Available at ise-set ang tourist sa completed
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];
$data = json_decode(file_get_contents("php://input"), true);
$customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;

try {
    mysqli_begin_transaction($con);

    // tapos na yung tour so gawing Available ulit yung guide at iclear yung current tourist assignment nila para makapasok sa queue ulit
    $updateGuideSql = "UPDATE tour_guides SET current_status = 'Available', current_tourist_id = NULL, became_available_at = NOW() WHERE guide_id = ?";
    $stmtG = mysqli_prepare($con, $updateGuideSql);
    mysqli_stmt_bind_param($stmtG, "i", $guide_id);
    if (!mysqli_stmt_execute($stmtG)) {
        throw new Exception("Failed to update guide status.");
    }

    if ($customer_id) {
        // inaupdate yung tourist record para maging completed yung status ng tourist at maset yung completed_at timestamp
        $updateTouristSql = "UPDATE tourists SET status = 'completed', completed_at = NOW(), guide_id = ? WHERE customer_id = ?";
        $stmtT = mysqli_prepare($con, $updateTouristSql);
        mysqli_stmt_bind_param($stmtT, "ii", $guide_id, $customer_id);
        if (!mysqli_stmt_execute($stmtT)) {
            throw new Exception("Failed to mark tourist as completed.");
        }
    }

    mysqli_commit($con);
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>