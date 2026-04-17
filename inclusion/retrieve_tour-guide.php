<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../asset/config.php';

$sql = "SELECT guide_id, first_name, last_name, email, current_status, last_dispatch_time FROM tour_guides ORDER BY guide_id";
$result = mysqli_query($con, $sql);

if ($result) {
    $guides = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $guides[] = $row;
    }

    echo json_encode(["status" => "Success", "data" => $guides]);
} else {
    echo json_encode(["status" => "Error", "message" => "Failed to retrieve tour guide data."]);
}
?>