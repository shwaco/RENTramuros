<?php
header('Content-Type: application/json');
require_once '../config.php';

$data = json_decode(file_get_contents('php://input'), true);

$db = new Database();
$conn = $db->getConnection();

try {

    $conn->beginTransaction();

    // tiga hanap ng naunang tour guide sa queue
    $stmt = $conn->query("
        SELECT guide_id, first_name 
        FROM tour_guides 
        WHERE current_status = 'Available' 
        ORDER BY became_available_at ASC 
        LIMIT 1 
        FOR UPDATE
    ");
    $assignedGuide = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($assignedGuide) {
        $guideId = $assignedGuide['guide_id'];

        // tiga insert ng tourist tas minamark as active
        $stmtT = $conn->prepare("
            INSERT INTO tourists (first_name, last_name, email, phone_number, service_type, status, guide_id, called_at) 
            VALUES (?, ?, ?, ?, ?, 'active', ?, NOW())
        ");
        $stmtT->execute([
            $data['first_name'], 
            $data['last_name'], 
            $data['email'], 
            $data['phone_number'], 
            $data['service_type'],
            $guideId
        ]);
        
        $touristId = $conn->lastInsertId();

        // pang update ng guide as busy and paglink ng tourist sa db
        $stmtG = $conn->prepare("UPDATE tour_guides SET current_status = 'Busy', current_tourist_id = ? WHERE guide_id = ?");
        $stmtG->execute([$touristId, $guideId]);

        $conn->commit();

        echo json_encode([
            'success' => true, 
            'message' => 'Match found!', 
            'guide_name' => $assignedGuide['first_name']
        ]);
    } else {
        $conn->rollBack();
        echo json_encode([
            'success' => false, 
            'message' => 'All Tour Guides are currently busy. Please try again later.'
        ]);
    }

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>