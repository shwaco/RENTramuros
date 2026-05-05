<?php
session_start();
header('Content-Type: application/json');
require_once('../../../config/config.php');

// POST endpoint — ito yung kinocall ng clockIn() function sa queue.js
// nag-aaupdate ng current_status ng guide sa 'Clocked In' at nire-record yung became_available_at timestamp

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // ise-set yung status as 'Clocked In' at irerecord yung timestamp para ma-track kung kelan nag-clock in yung guide 
    // yung timestamp na ito ang gagamitin para i-prioritize sila sa queue pagdating ng time
    // UPDATE statement — ang became_available_at yung main sort key ng queue; mas maaga = mas mataas sa pila
    $sql = "UPDATE tour_guides SET current_status = 'Clocked In', became_available_at = NOW() WHERE guide_id = ?";
    $stmt = $con->prepare($sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>