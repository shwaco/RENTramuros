<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // Set to 'Clocked In' and record the timestamp
    $sql = "UPDATE tour_guides SET current_status = 'Clocked In', became_available_at = NOW() WHERE guide_id = ?";
    $stmt = $con->prepare($sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>