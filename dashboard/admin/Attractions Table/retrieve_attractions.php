<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once '../asset/connect_phpmyadmin.php';

$fetch_sql = "SELECT attraction_id, attraction_name, description, entrance_fee, operating_hours, image_file FROM Attractions";
$result = mysqli_query($con, $fetch_sql);

if ($result) {
    $attractions_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $attractions = array(
            "attraction_id" => $row['attraction_id'],
            "attraction_name" => $row['attraction_name'],
            "description" => $row['description'],
            "entrance_fee" => $row['entrance_fee'],
            "operating_hours" => $row['operating_hours'],
            "image_file" => $row['image_file']
        );

        array_push($attractions_array, $attractions);
    }

    if (count($attractions_array) > 0) {
        echo json_encode(["status" => "success", "data" => $attractions_array]);
    } else {
        echo json_encode(["status" => "error", "message" => "No attractions found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve attractions."]);
}
?>