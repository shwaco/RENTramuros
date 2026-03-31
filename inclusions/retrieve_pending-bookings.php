<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

// Step up one folder to find the connection file
include_once('../asset/connect_phpmyadmin.php');

$sql = "SELECT * FROM Reservations WHERE status = 'pending'";
// Check if your connection file uses $con or $conn!
$result = mysqli_query($con, $sql); 

$bookings = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}

echo json_encode(["status" => "success", "data" => $bookings]);
?>