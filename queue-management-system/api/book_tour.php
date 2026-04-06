<?php
header('Content-Type: application/json');
require_once('../../config/config.php');

$data = json_decode(file_get_contents('php://input'), true);

$db = new Database();
$conn = $db->getConnection();

try {

    mysqli_begin_transaction($con);

    // tiga hanap ng naunang tour guide sa queue
    $sql = "SELECT guide_id, first_name FROM tour_guides WHERE current_status = 'Available' ORDER BY became_available_at ASC LIMIT 1 FOR UPDATE";
    $result = mysqli_query($con, $sql);
    $assignedGuide = mysqli_fetch_assoc($result);

    if ($assignedGuide) {
        $guideId = $assignedGuide['guide_id'];

        // tiga insert ng tourist tas minamark as active
        $insertTouristSql = "INSERT INTO tourists (first_name, last_name, email, phone_number, service_type, status, guide_id, called_at) VALUES (?, ?, ?, ?, ?, 'active', ?, NOW())";
        $stmtT = mysqli_prepare($con, $insertTouristSql);
        mysqli_stmt_bind_param($stmtT, "sssssi", $data['first_name'], $data['last_name'], $data['email'], $data['phone_number'], $data['service_type'], $guideId);
        mysqli_stmt_execute($stmtT);
        
        $touristId = mysqli_insert_id($con);

        // pang update ng guide as busy and paglink ng tourist sa db
        $updateGuideSql = "UPDATE tour_guides SET current_status = 'Busy', current_tourist_id = ? WHERE guide_id = ?";
        $stmtG = mysqli_prepare($con, $updateGuideSql);
        mysqli_stmt_bind_param($stmtG, "ii", $touristId, $guideId);
        mysqli_stmt_execute($stmtG);

        mysqli_commit($con);

        echo json_encode(['success' => true, 'message' => 'Match found!', 'guide_name' => $assignedGuide['first_name']]);
    } else {
        mysqli_rollback($con);
        echo json_encode(['success' => false, 'message' => 'All Tour Guides are currently busy. Please try again later.']);
    }

} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>