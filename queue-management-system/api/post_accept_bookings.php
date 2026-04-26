<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para i-accept ng guide yung selected tourist —
// ise-set dito yung tourist status sa serving at yung guide status sa On Tour
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']); exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$customer_id = $data['customer_id'] ?? null;
$guide_id = $_SESSION['guide_id'];

try {
    mysqli_begin_transaction($con);

    // tiga-check muna kung available pa yung tourist para maiwasan ang double-claim ng dalawang guide
    $stmtC = mysqli_prepare($con, "SELECT status FROM tourists WHERE customer_id = ? FOR UPDATE");
    mysqli_stmt_bind_param($stmtC, "i", $customer_id);
    mysqli_stmt_execute($stmtC);
    $touristResult = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtC));

    $validStatuses = ['waiting', 'active'];
    if (!$touristResult || !in_array($touristResult['status'], $validStatuses)) {
        throw new Exception("Another guide already claimed this tourist!");
    }

    // ina-update yung tourist record — ise-set yung status niya sa serving
    $stmtT = mysqli_prepare($con, "UPDATE tourists SET status = 'serving', called_at = NOW(), guide_id = ? WHERE customer_id = ?");
    mysqli_stmt_bind_param($stmtT, "ii", $guide_id, $customer_id);
    mysqli_stmt_execute($stmtT);

    // ina-update din yung guide status sa "On Tour" at ililinkto sa current tourist
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