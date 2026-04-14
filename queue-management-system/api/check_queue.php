<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API na nagbabalik ng current queue status ng guide.
// Ipinapakita dito kung nasa Available queue pa siya at kung ano ang kanyang current position.
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false]); exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // tiga kuha ng current status at became_available_at timestamp para malaman ang queue position
    $stmtInfo = mysqli_prepare($con, "SELECT current_status, became_available_at FROM tour_guides WHERE guide_id = ?");
    mysqli_stmt_bind_param($stmtInfo, "i", $guide_id);
    mysqli_stmt_execute($stmtInfo);
    $guideInfo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtInfo));

    $position = 0;
    
    // if Available pa ang guide, kinacalculate nito yung exact position niya sa queue
    if ($guideInfo['current_status'] === 'Available') {
        $stmtP = mysqli_prepare($con, "SELECT COUNT(*) + 1 as pos FROM tour_guides WHERE current_status = 'Available' AND became_available_at < ?");
        mysqli_stmt_bind_param($stmtP, "s", $guideInfo['became_available_at']);
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