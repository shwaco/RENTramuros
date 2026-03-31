<?php

include_once('connect_phpmyadmin.php');

$sql = "SELECT * FROM Attractions";
$result = mysqli_query($con, $sql);

$attractions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $attractions[] = $row;
}

echo json_encode(["status" => "success", "data" => $attractions]);


?>