<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para i-accept ng guide ang selected tourist.
// Sineset dito ang tourist status sa serving at ang guide status sa On tour.
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']); exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$customer_id = $data['customer_id'] ?? null;
$guide_id = $_SESSION['guide_id'];

try {
    mysqli_begin_transaction($con);

    // tiga check if available pa ung tourist para maiwasan ang double-claim
    $stmtC = mysqli_prepare($con, "SELECT status FROM tourists WHERE customer_id = ? FOR UPDATE");
    mysqli_stmt_bind_param($stmtC, "i", $customer_id);
    mysqli_stmt_execute($stmtC);
    $touristResult = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtC));

    $validStatuses = ['waiting', 'active'];
    if (!$touristResult || !in_array($touristResult['status'], $validStatuses)) {
        throw new Exception("Another guide already claimed this tourist!");
    }

    // tiga update ng tourist record para maging serving yung status ng tourist and mailink sa current guide.
    $stmtT = mysqli_prepare($con, "UPDATE tourists SET status = 'serving', called_at = NOW(), guide_id = ? WHERE customer_id = ?");
    mysqli_stmt_bind_param($stmtT, "ii", $guide_id, $customer_id);
    mysqli_stmt_execute($stmtT);

    // tiga update ng status ng guide as "On tour" and para malink sa current tourist
    $stmtG = mysqli_prepare($con, "UPDATE tour_guides SET current_status = 'On Tour', current_tourist_id = ? WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtG, "ii", $customer_id, $guide_id);
    mysqli_stmt_execute($stmtG);

    mysqli_commit($con);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>