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
    // may limit na 10 para hanggang 10 lang makikita sa dashboard for now
    $sql = "SELECT queue_number, first_name, last_name, service_type AS vehicle_type, completed_at FROM tourists WHERE guide_id = ? AND status = 'completed' ORDER BY completed_at DESC LIMIT 10";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $history = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'history' => $history]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>