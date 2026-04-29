<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// Daily queue stats using booking_history table.
// Status mapping: Pending = waiting, Accepted = serving, Done = completed
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

try {
    $stats = [];
    
     // COUNT query para sa lahat ng 'Pending' bookings  — ito yung mga tourist na naghihintay pa ng guide
    $resultW = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE status = 'Pending' AND booking_date = CURDATE()");
    $stats['waiting'] = mysqli_fetch_assoc($resultW)['count'];

    // COUNT query para sa lahat ng 'Accepted' bookings — ito yung mga tour na kasalukuyang in-progress
    $resultS = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE status = 'Accepted' AND booking_date = CURDATE()");
    $stats['serving'] = mysqli_fetch_assoc($resultS)['count'];

    // COUNT query para sa lahat ng 'Done' bookings ngayon — ito yung mga natapos nang tour
    $resultC = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE status = 'Done' AND booking_date = CURDATE()");
    $stats['completed'] = mysqli_fetch_assoc($resultC)['count'];

    // COUNT query para sa lahat ng bookings regardless ng status
    $resultT = mysqli_query($con, "SELECT COUNT(*) as count FROM booking_history WHERE booking_date = CURDATE()");
    $stats['today_total'] = mysqli_fetch_assoc($resultT)['count'];

    echo json_encode(['success' => true, 'data' => $stats]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>