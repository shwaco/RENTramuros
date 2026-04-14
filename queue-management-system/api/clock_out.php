<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para i-clock out ang guide. minamark nito bilang Offline at dine-delete ang session para ma-log out ang guide
if (isset($_SESSION['guide_id'])) {
    $guide_id = $_SESSION['guide_id'];

    try {
        // Minamark ang guide as Offline para hindi na siya masama sa queue.
        $sql = "UPDATE tour_guides SET current_status = 'Offline', current_tourist_id = NULL WHERE guide_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $guide_id);
        mysqli_stmt_execute($stmt);
    } catch (Exception $e) {
        error_log("Clock Out Error: " . $e->getMessage());
    }
} 

session_unset();
session_destroy();
echo json_encode(['success' => true]);
exit();
?>