<?php
session_start();

// API para icancel ang current tourist assignment
// sineset nito ang tourist pabalik sa active queue aandt nirereset ung guide status
require_once '../../config/config.php'; 

header('Content-Type: application/json');

// tiga check if user is logged in
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in.']);
    exit;
}

$guide_id = $_SESSION['guide_id'];

// tiga kuha ng JSON from the front-end request.
$data = json_decode(file_get_contents('php://input'), true);
$customer_id = $data['customer_id'] ?? null;

if (!$customer_id) {
    echo json_encode(['success' => false, 'error' => 'No tourist ID provided.']);
    exit;
}

try {
    mysqli_begin_transaction($con);

    //  tiga balik sa active queue and tiga tanggal ng tour guide assignment and called_at na oras
    $stmt1 = mysqli_prepare($con, "UPDATE tourists SET status = 'active', guide_id = NULL, called_at = NULL WHERE customer_id = ?");
    mysqli_stmt_bind_param($stmt1, "i", $customer_id);
    if (!mysqli_stmt_execute($stmt1)) {
        throw new Exception("Failed to update tourist.");
    }

    // makes the guide available again by resetting their status and current tourist assignment
    $stmt2 = mysqli_prepare($con, "UPDATE tour_guides SET current_status = 'Available', current_tourist_id = NULL, became_available_at = CURRENT_TIMESTAMP WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $guide_id);
    if (!mysqli_stmt_execute($stmt2)) {
         throw new Exception("Failed to update guide status.");
    }

    mysqli_commit($con);
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>