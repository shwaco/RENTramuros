<?php
session_start();
require_once '../config.php';

if (isset($_SESSION['guide_id'])) {
    $guide_id = $_SESSION['guide_id'];
    $db = new Database();
    $conn = $db->getConnection();

    try {
        // minamark as offline yung guide para di makakuha ng tourist
        $stmt = $conn->prepare("UPDATE tour_guides SET current_status = 'Offline', current_tourist_id = NULL WHERE guide_id = ?");
        $stmt->execute([$guide_id]);
    } catch (Exception $e) {
        // If the database fails, we still want to log them out locally
        error_log("Clock Out Error: " . $e->getMessage());
    }
} 

session_unset();
session_destroy();

header("Location: ../../login_tour_guide.php");
exit();
?>