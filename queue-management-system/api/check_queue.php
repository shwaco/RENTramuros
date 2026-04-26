<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

$sweep_idle_sql = "UPDATE tour_guides SET current_status = 'Offline' WHERE current_status IN ('Clocked In', 'Online') AND last_active_at < (NOW() - INTERVAL 120 SECOND)";
mysqli_query($con, $sweep_idle_sql);

$sweep_queue_sql = "UPDATE tour_guides SET current_status = 'Offline' WHERE current_status = 'Queuing' AND last_active_at < (NOW() - INTERVAL 1800 SECOND)";
mysqli_query($con, $sweep_queue_sql);


if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false]); exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // dahil piniping ng browser to, ina-update nito yung database to prove na online pa yung guide
    $heartbeat_sql = "UPDATE tour_guides SET last_active_at = NOW() WHERE guide_id = ?";
    $stmtH = mysqli_prepare($con, $heartbeat_sql);
    mysqli_stmt_bind_param($stmtH, "i", $guide_id);
    mysqli_stmt_execute($stmtH);


    // here yung tie breaker logic para ma-determine yung position ng guide sa queue
    $stmtInfo = mysqli_prepare($con, "SELECT current_status, became_available_at FROM tour_guides WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtInfo, "i", $guide_id);
    mysqli_stmt_execute($stmtInfo);
    $guideInfo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtInfo));

    $position = 0;
    
    if ($guideInfo['current_status'] === 'Queuing') {
        $stmtP = mysqli_prepare($con, "SELECT COUNT(*) + 1 as pos FROM tour_guides WHERE current_status = 'Queuing' AND (became_available_at < ? OR (became_available_at = ? AND guide_id < ?))");
        mysqli_stmt_bind_param($stmtP, "ssi", $guideInfo['became_available_at'], $guideInfo['became_available_at'], $guide_id);
        mysqli_stmt_execute($stmtP);
        $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtP));
        $position = (int)$result['pos'];
    }

    echo json_encode([
        'success' => true, 
        'status' => $guideInfo['current_status'], 
        'position' => $position
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
?>