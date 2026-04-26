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
    // tiga-check muna kung On Tour pa yung guide para iwas clock out habang may tour
    $check_sql = "SELECT current_status FROM tour_guides WHERE guide_id = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $guide_id);
    mysqli_stmt_execute($check_stmt);
    $status_result = mysqli_fetch_assoc(mysqli_stmt_get_result($check_stmt));

    if ($status_result && $status_result['current_status'] === 'On Tour') {
        echo json_encode(['success' => false, 'message' => 'You cannot clock out while on an active tour.']);
        exit();
    }

    // ise-set status to 'Online' para bumalik sila sa state ng dashboard where they have to clock in ulit
    $sql = "UPDATE tour_guides SET current_status = 'Online', current_tourist_id = NULL WHERE guide_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log("Clock Out Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred.']);
}
?>