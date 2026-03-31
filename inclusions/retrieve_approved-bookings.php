<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

include_once('./connect_phpmyadmin.php');

$sql = "SELECT * FROM Reservations WHERE status = 'approved'";
$result = mysqli_query($con, $sql);

$bookings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bookings[] = $row;
}

echo json_encode(["status" => "success", "data" => $bookings]);


?>