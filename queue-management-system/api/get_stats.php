<?php
header('Content-Type: application/json');
require_once '../../asset/connect_phpmyadmin.php';

try {
    $stats = [];
    
    // kinacount yung mga naghihintay na tourist
    $resultW = mysqli_query($con, "SELECT COUNT(*) as count FROM tourists WHERE status = 'waiting' AND DATE(created_at) = CURDATE()");
    $stats['waiting'] = mysqli_fetch_assoc($resultW)['count'];
    
    // kinacount yung mga ongoing na tours
    $resultS = mysqli_query($con, "SELECT COUNT(*) as count FROM tourists WHERE status = 'serving' AND DATE(created_at) = CURDATE()");
    $stats['serving'] = mysqli_fetch_assoc($resultS)['count'];
    
    // kinacount yng mga nacomplete na tours
    $resultC = mysqli_query($con, "SELECT COUNT(*) as count FROM tourists WHERE status = 'completed' AND DATE(created_at) = CURDATE()");
    $stats['completed'] = mysqli_fetch_assoc($resultC)['count'];
    
    // kinacaount kung ilan yung tourists
    $resultT = mysqli_query($con, "SELECT COUNT(*) as count FROM tourists WHERE DATE(created_at) = CURDATE()");
    $stats['today_total'] = mysqli_fetch_assoc($resultT)['count'];
    
    echo json_encode([
        'success' => true,
        'data' => $stats
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>