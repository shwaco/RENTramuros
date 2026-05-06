<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../../shared/middleware/auth_check.php';
/** @var mysqli $con */

requireRole(['tourist']);

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed. Use POST.']);
    exit();
}

$data       = json_decode(file_get_contents('php://input'), true);
$unique_id  = isset($data['unique_id'])  ? trim($data['unique_id'])  : null;
$new_status = isset($data['status'])     ? trim($data['status'])     : null;

if (!$unique_id || !$new_status) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing booking_request_id or status.']);
    exit();
}

$allowed = ['Done', 'Cancelled'];
if (!in_array($new_status, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit();
}

try {
    mysqli_begin_transaction($con);

    $stmtCheck = mysqli_prepare($con,
        "SELECT booking_request_id, guide_id, status 
         FROM booking_history
         WHERE unique_id = ? AND tourist_id = ? FOR UPDATE"
    );
    mysqli_stmt_bind_param($stmtCheck, 'si', $unique_id, $user_id);
    mysqli_stmt_execute($stmtCheck);
    $booking = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCheck));

    if (!$booking) {
        throw new Exception('Booking not found or does not belong to you.');
    }

    if ($new_status === 'Done' && $booking['status'] !== 'Accepted') {
        throw new Exception("Cannot mark as Done (current status: {$booking['status']}).");
    }

    if ($new_status === 'Cancelled' && !in_array($booking['status'], ['Pending', 'Accepted'], true)) {
        throw new Exception("Cannot cancel a booking with status: {$booking['status']}.");
    }

    $booking_request_id = $booking['booking_request_id'];

    $stmtUpdate = mysqli_prepare($con,
        "UPDATE booking_history SET status = ? WHERE booking_request_id = ?"
    );
    mysqli_stmt_bind_param($stmtUpdate, 'si', $new_status, $booking_request_id);
    mysqli_stmt_execute($stmtUpdate);

    if ($new_status === 'Done' && $booking['guide_id']) {
        $stmtGuide = mysqli_prepare($con,
            "UPDATE tour_guides
             SET current_status = 'Queuing', current_tourist_id = NULL, became_available_at = NOW()
             WHERE guide_id = ?"
        );
        mysqli_stmt_bind_param($stmtGuide, 'i', $booking['guide_id']);
        mysqli_stmt_execute($stmtGuide);
    }

    mysqli_commit($con);
    echo json_encode(['success' => true, 'message' => "Booking marked as {$new_status}."]);

} catch (Exception $e) {
    mysqli_rollback($con);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>