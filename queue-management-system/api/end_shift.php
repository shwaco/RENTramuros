<?php
session_start();
require_once '../config.php';

if (isset($_SESSION['guide_id'])) {
    $guide_id = $_SESSION['guide_id'];
    $db = new Database();
    $conn = $db->getConnection();

    try {
        // Tell the database Ernesto is going home!
        $stmt = $conn->prepare("UPDATE tour_guides SET current_status = 'Offline', current_tourist_id = NULL WHERE guide_id = ?");
        $stmt->execute([$guide_id]);
    } catch (Exception $e) {
        error_log("End Shift Error: " . $e->getMessage());
    }
} 

// Destroy the session
session_unset();
session_destroy();

// Redirect back to login
header("Location: ../../login_tour_guide.php");
exit();
?>