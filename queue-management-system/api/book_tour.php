<?php
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para mag request ng bagong tour mula sa tourist side
// ini-insert ung tourist sa waiting queue and ibinabalik ang bagong tourist_id.
$data = json_decode(file_get_contents('php://input'), true);

try {
    // 1. Siguraduhing may required na tourist data bago i-save.
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
        throw new Exception("Missing required tourist information.");
    }

    // sinesave yung tourist as "waiting" and Wala pa siyang assigned guide dito.
    $insertTouristSql = "INSERT INTO tourists (first_name, last_name, email, phone_number, service_type, status, called_at) VALUES (?, ?, ?, ?, ?, 'waiting', NOW())";
    $stmtT = mysqli_prepare($con, $insertTouristSql);
    
    mysqli_stmt_bind_param(
        $stmtT, 
        "sssss", 
        $data['first_name'], 
        $data['last_name'], 
        $data['email'], 
        $data['phone_number'], 
        $data['service_type']
    );
    
    if (mysqli_stmt_execute($stmtT)) {
        // tiga kuha ng bagong tourist_id para ireturn sa front-end at magamit sa future API calls
        $touristId = mysqli_insert_id($con);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Tour requested successfully! Please wait while we connect you with a tour guide.',
            'tourist_id' => $touristId
        ]);
    } else {
        throw new Exception("Database error: Could not insert tourist.");
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>