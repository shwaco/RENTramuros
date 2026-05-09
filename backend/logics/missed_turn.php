<?php
session_start();
header('Content-Type: application/json');
require_once('../config/config.php');

// POST endpoint — kinocall ng startClaimTimer() sa queue.js kapag naubos na yung 15 seconds timer
// nag-aupdate ng became_available_at sa NOW() para mapush ang guide sa dulo ng queue 
// epektibong parusang timestamp reset bilang penalty sa hindi pag-pick ng tourist

// cinocall tong api na to if namiss yung turn ng guide sa queue
// naguupdate lang to ng became_available_at timestamp para mapush yung guide sa pinakabagong position ng queue
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// tiga update ng row ng guide sa database para ma-refresh yung availability timestamp niya
$sql = "UPDATE tour_guides SET became_available_at = NOW() WHERE guide_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);

echo json_encode(['success' => true]);
?>