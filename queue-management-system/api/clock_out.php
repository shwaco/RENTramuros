<?php
session_start();
header('Content-Type: application/json');
require_once '../../asset/connect_phpmyadmin.php';

if (isset($_SESSION['guide_id'])) {
    $guide_id = $_SESSION['guide_id'];

    try {
        // minamark as offline yung guide para di makakuha ng tourist
        $sql = "UPDATE tour_guides SET current_status = 'Offline', current_tourist_id = NULL WHERE guide_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $guide_id);
        mysqli_stmt_execute($stmt);
    } catch (Exception $e) {
        // If the database fails, illog out pa rin
        error_log("Clock Out Error: " . $e->getMessage());
    }
} 

session_unset();
session_destroy();
echo json_encode(['success' => true]);
exit();
?>