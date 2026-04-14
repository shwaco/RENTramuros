<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para ijoin ang guide sa queue at gawing Available ang guide para makakuha ng tourist
// magseset tto ng current_status at became_available_at para maayos ang queue order
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$guide_id = $_SESSION['guide_id'];

try {
    // ginagawang Available ang tour guide and tiga reset ng queue timer nila para makapasok sa pila
    $sql = "UPDATE tour_guides SET current_status = 'Available', became_available_at = NOW() WHERE guide_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guide_id);
    mysqli_stmt_execute($stmt);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>