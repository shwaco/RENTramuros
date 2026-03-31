<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

include_once('connect_phpmyadmin.php');

$sql = "SELECT attraction_id, attraction_name, entrance_fee, operating_hours FROM Attractions";
$result = mysqli_query($con, $sql);

$attractions_array = array();
if ($result && mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($attractions_array, $row);
    }
        echo json_encode(["status" => "success", "data" => $attractions_array]);
    } else {
        echo json_encode(["status" => "success", "message" => "No attractions found", "data" => []]);
    }

?>