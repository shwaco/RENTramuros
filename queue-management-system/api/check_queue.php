<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// GET endpoint — kinocall to ng JS polling (startPolling) every 5 second
// nagre-return din siya ng JSON na may current_status at queue position ng guide



// Idle sweep — also catches 'Available' para pag afk yung guide, mare-reset siya sa 'Offline' at mawawala sa queue jabang nasa "available" state
$sweep_idle_sql = "UPDATE tour_guides SET current_status = 'Offline' 
                   WHERE current_status IN ('Clocked In', 'Online', 'Available') 
                   AND last_active_at < (NOW() - INTERVAL 120 SECOND)";
mysqli_query($con, $sweep_idle_sql);

$sweep_queue_sql = "UPDATE tour_guides SET current_status = 'Offline' 
                    WHERE current_status = 'Queuing' 
                    AND last_active_at < (NOW() - INTERVAL 1800 SECOND)";
mysqli_query($con, $sweep_queue_sql);

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false]); exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // Heartbeat — ina-UPDATE ung last_active_at sa column ng table ng tour guide in every poll request para malaman yung server na buhay pa ang session ng guide
    $stmtH = mysqli_prepare($con, "UPDATE tour_guides SET last_active_at = NOW() WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtH, "i", $guide_id);
    mysqli_stmt_execute($stmtH);

    $stmtInfo = mysqli_prepare($con, "SELECT current_status, became_available_at FROM tour_guides WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtInfo, "i", $guide_id);
    mysqli_stmt_execute($stmtInfo);
    $guideInfo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtInfo));

    $position = 0;

    // COUNT query para ma-compute ang queue position ng guide —
    // ginagamit ang became_available_at para i-rank sila; kung same timestamp, ginagamit yung guide_id as tiebreaker

    if ($guideInfo['current_status'] === 'Queuing') {
        $stmtP = mysqli_prepare($con,
            "SELECT COUNT(*) + 1 as pos FROM tour_guides 
             WHERE current_status = 'Queuing' 
               AND (became_available_at < ? OR (became_available_at = ? AND guide_id < ?))");
        mysqli_stmt_bind_param($stmtP, "ssi",
            $guideInfo['became_available_at'],
            $guideInfo['became_available_at'],
            $guide_id);
        mysqli_stmt_execute($stmtP);
        $result   = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtP));
        $position = (int)$result['pos'];
    }

    echo json_encode([
        'success'  => true,
        'status'   => $guideInfo['current_status'],
        'position' => $position
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
?>