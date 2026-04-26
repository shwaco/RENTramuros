<?php
session_start();
header('Content-Type: application/json');
require_once('../../config.php/config.php');

// API para kunin yung daily statistics ng queue at tours
// ginagamit to sa dashboard cards para makita agad yung current state ng queue, tourists, etc...
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

try {
    $stats = [];
    
    // counter ng waiting tourists for today
    $resultW = mysqli_query($con, "SELECT COUNT(*) as count FROM tourists WHERE status = 'waiting' AND DATE(created_at) = CURDATE()");
    $stats['waiting'] = mysqli_fetch_assoc($resultW)['count'];
    
    // counter ng serving tourists for today
    $resultS = mysqli_query($con, "SELECT COUNT(*) as count FROM tourists WHERE status = 'serving' AND DATE(created_at) = CURDATE()");
    $stats['serving'] = mysqli_fetch_assoc($resultS)['count'];
    
    // counter ng completed tours for today
    $resultC = mysqli_query($con, "SELECT COUNT(*) as count FROM tourists WHERE status = 'completed' AND DATE(created_at) = CURDATE()");
    $stats['completed'] = mysqli_fetch_assoc($resultC)['count'];
    
    // counter ng total tourists for today
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