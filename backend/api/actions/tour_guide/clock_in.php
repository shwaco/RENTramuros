<?php
session_start();
header('Content-Type: application/json');
require_once('../../../config/config.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['user_id'];

try {
    $sql = "UPDATE tour_guides SET current_status = 'Clocked In', became_available_at = NOW(), last_active_at = NOW() WHERE guide_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>