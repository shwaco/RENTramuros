<?php
session_start();
require_once '../config.php';

if (isset($_SESSION['guide_id'])) {
    $guide_id = $_SESSION['guide_id'];
    $db = new Database();
    $conn = $db->getConnection();

    try {
        // Mark the guide as completely Offline so they don't get tourists assigned
        $stmt = $conn->prepare("UPDATE tour_guides SET current_status = 'Offline', current_tourist_id = NULL WHERE guide_id = ?");
        $stmt->execute([$guide_id]);
    } catch (Exception $e) {
        // If the database fails, we still want to log them out locally
        error_log("Clock Out Error: " . $e->getMessage());
    }
} 

// Destroy the session (log out of the browser)
session_unset();
session_destroy();

// Go back to the login page
header("Location: ../../login_tour_guide.php");
exit();
?>