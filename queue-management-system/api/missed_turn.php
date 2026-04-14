<?php
session_start();
header('Content-Type: application/json');
require_once('../../config/config.php');

// ito yung API endpoint na tinatawag kapag na-miss ng tour guide yung turn niya
// pna-update lang nito ang became_available_at timestamp para mapush yung guide sa pinakabagong posisyon ng queue
if (!isset($_SESSION['guide_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// inaaupdate yung row ng guide sa database para marefresh yung kanyang availability timestamp
$sql = "UPDATE tour_guides SET became_available_at = NOW() WHERE guide_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['guide_id']);
mysqli_stmt_execute($stmt);

echo json_encode(['success' => true]);
?>