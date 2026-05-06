<?php
header('Content-Type: application/json');
require_once __DIR__. '/../../../config/config.php';
require_once __DIR__. '/../../../../shared/middleware/auth_check.php';
// Called by the TOURIST when they mark the tour as Done.
// Also resets the assigned guide back to Queuing automatically.
requireRole(['tourist']);

$user_id = $_SESSION['user_id'];
$userRole = $_SESSION['role'];
$data       = json_decode(file_get_contents("php://input"), true);
$booking_id = isset($data['booking_request_id']) ? (int)$data['booking_request_id'] : null;

if (!$booking_id) {
    echo json_encode(['success' => false, 'message' => 'Missing booking ID.']);
    exit();
}

try {
    mysqli_begin_transaction($con);

    // Get the booking to find the assigned guide
    // SELECT FOR UPDATE — naglo-lock ng row para maiwasan ang race condition kung may ibang request na mag-aaccess nito
    $stmtCheck = mysqli_prepare($con,
        "SELECT guide_id, status FROM booking_history 
         WHERE booking_request_id = ? AND tourist_id = ? FOR UPDATE"
    );
    mysqli_stmt_bind_param($stmtCheck, "ii", $booking_id, $user_id);
    mysqli_stmt_execute($stmtCheck);
    $booking = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCheck));

    if (!$booking) {
        throw new Exception("Booking not found.");
    }
    // Mark booking as Accepted
    // hindi pwedeng i-complete ang booking na 'Done' na o 'Pending' pa lang
    if ($booking['status'] !== 'Accepted') {
        throw new Exception("This booking cannot be completed (status: {$booking['status']}).");
    }

    // Mark booking as Done
    $stmtB = mysqli_prepare($con,
        "UPDATE booking_history SET status = 'Done' WHERE booking_request_id = ?"
    );
    mysqli_stmt_bind_param($stmtB, "i", $booking_id);
    mysqli_stmt_execute($stmtB);

    // nire-reset ang guide pabalik sa 'Queuing' at nire-refresh yung became_available_at
    // para mabalik siya sa dulo ng queue at makakuha ng bagong tourist
    if ($booking['guide_id']) {
        $stmtG = mysqli_prepare($con,
            "UPDATE tour_guides 
             SET current_status = 'Queuing', current_tourist_id = NULL, became_available_at = NOW()
             WHERE guide_id = ?"
        );
        mysqli_stmt_bind_param($stmtG, "i", $booking['guide_id']);
        mysqli_stmt_execute($stmtG);
    }

    mysqli_commit($con);
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>