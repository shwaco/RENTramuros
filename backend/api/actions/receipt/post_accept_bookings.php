<?php
session_start();
header('Content-Type: application/json');
require_once('../../../config/config.php');

// POST endpoint — kinocall ng executeAcceptTour() sa tour_details.js
// request body (JSON): { tourist_id: int } — ang "tourist_id" here yung booking_request_id
// gumagamit ng DB transaction + SELECT FOR UPDATE para maiwasan ang race condition (double-claim)

// Guide accepts a booking — sets booking_history.status to 'Accepted'
// and links guide to tourist via tour_guides.current_tourist_id
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']); exit();
}

$data        = json_decode(file_get_contents('php://input'), true);
$booking_id  = $data['tourist_id'] ?? null;  // frontend sends tourist_id which is now booking_request_id
$guide_id    = $_SESSION['guide_id'];

try {
    mysqli_begin_transaction($con);

    // Lock row and verify it's still Pending to prevent double-claim
    $stmtC = mysqli_prepare($con, "SELECT status, tourist_id FROM booking_history WHERE booking_request_id = ? FOR UPDATE");
    mysqli_stmt_bind_param($stmtC, "i", $booking_id);
    mysqli_stmt_execute($stmtC);
    $booking = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtC));

    if (!$booking || $booking['status'] !== 'Pending') {
        throw new Exception("Another guide already claimed this booking!");
    }

    // Mark booking as Accepted and assign guide
    $stmtB = mysqli_prepare($con, "UPDATE booking_history SET status = 'Accepted', guide_id = ? WHERE booking_request_id = ?");
    mysqli_stmt_bind_param($stmtB, "ii", $guide_id, $booking_id);
    mysqli_stmt_execute($stmtB);

    // Set guide to On Tour — current_tourist_id stores the tourist_id (NULL for walk-ins)
    $stmtG = mysqli_prepare($con, "UPDATE tour_guides SET current_status = 'On Tour', current_tourist_id = ? WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtG, "ii", $booking['tourist_id'], $guide_id);
    mysqli_stmt_execute($stmtG);

    mysqli_commit($con);
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>