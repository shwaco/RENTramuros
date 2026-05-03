<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// 1. IDLE SWEEP (Online/Available) -> 10 mins (600 seconds) ito yung if ever man na accidentally mawala sa site habang online yung status niya
// automatic na ise-set sa 'Offline' yung status niya pag di siya nagparamdam ng 10 mins (600 seconds)
$sweep_idle_sql = "UPDATE tour_guides SET current_status = 'Offline' 
                   WHERE current_status IN ('Online', 'Available') 
                   AND last_active_at < (NOW() - INTERVAL 600 SECOND)";
mysqli_query($con, $sweep_idle_sql);

// QUEUE SWEEP (Queuing/Clocked In) -> 30 mins (1800 seconds)
$sweep_queue_sql = "UPDATE tour_guides SET current_status = 'Offline' 
                    WHERE current_status IN ('Queuing', 'Clocked In') 
                    AND last_active_at < (NOW() - INTERVAL 1800 SECOND)";
mysqli_query($con, $sweep_queue_sql);

// I-check muna kung may tourist na naghihintay
$pending_check = mysqli_query($con, "SELECT booking_request_id FROM booking_history WHERE status = 'Pending' AND guide_id IS NULL LIMIT 1");

if (mysqli_num_rows($pending_check) > 0) {
    // Hanapin ang #1 guide sa queue
    $top_guide_query = mysqli_query($con, "SELECT guide_id FROM tour_guides WHERE current_status = 'Queuing' ORDER BY became_available_at ASC, guide_id ASC LIMIT 1");
    
    if ($top_guide_row = mysqli_fetch_assoc($top_guide_query)) {
        $top_guide_id = (int)$top_guide_row['guide_id'];
        
        // FIX: Hahayaan nating ang MySQL ang mag-compute ng 15 seconds para walang Timezone error!
        $missed_sql = "UPDATE tour_guides 
                       SET became_available_at = NOW() 
                       WHERE guide_id = $top_guide_id 
                       AND last_active_at < (NOW() - INTERVAL 15 SECOND)";
        
        mysqli_query($con, $missed_sql);
    }
}

if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false]); exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // inaupdate ung last_active_at sa column ng table ng tour guide in every poll request para malaman yung server na buhay pa ang session ng guide
    $stmtH = mysqli_prepare($con, "UPDATE tour_guides SET last_active_at = NOW() WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtH, "i", $guide_id);
    mysqli_stmt_execute($stmtH);

    $stmtInfo = mysqli_prepare($con, "SELECT current_status, became_available_at FROM tour_guides WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtInfo, "i", $guide_id);
    mysqli_stmt_execute($stmtInfo);
    $guideInfo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtInfo));

    $position = 0;

    // COUNT query para ma-compute ang queue position ng guide
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