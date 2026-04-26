<?php
session_start();
header('Content-Type: application/json');
require_once('../../config.php/config.php');

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // tiga-check muna kung Busy pa rin yung guide bago mag-allow ng clock out
    $check_sql = "SELECT current_status FROM tour_guides WHERE guide_id = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $guide_id);
    mysqli_stmt_execute($check_stmt);
    $status_result = mysqli_fetch_assoc(mysqli_stmt_get_result($check_stmt));

    if ($status_result && $status_result['current_status'] === 'Busy') {
        echo json_encode(['success' => false, 'message' => 'You cannot clock out while on an active tour. The tourist must finish the tour first.']);
        exit();
    }

    // pag hindi Busy, tapos na kaya pwedeng mag-clock out
    // ise-set to 'Offline' ang guide at ide-clear ang current tourist assignment para mag-end ang session
    $sql = "UPDATE tour_guides SET current_status = 'Offline', current_tourist_id = NULL WHERE guide_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    session_unset();
    session_destroy();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log("Clock Out Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred.']);
}
?>