<?php
// session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once 'asset/config.php';

// if(!isset($_SESSION['tourist_id']) && !isset($_SESSION['admin_id'])) {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
//     exit();
// }

$fetch_sql = "SELECT event_name, event_date, event_time, location, image_file FROM upcoming_events ORDER BY event_date ASC, event_time ASC";
$fetch_stmt = mysqli_prepare($con, $fetch_sql);
if(mysqli_stmt_execute($fetch_stmt)) {
    $result = mysqli_stmt_get_result($fetch_stmt);
    $events_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $event = array(
            "event_name" => $row['event_name'],
            "event_date" => $row['event_date'],
            "event_time" => $row['event_time'],
            "location" => $row['location'],
            "image_file" => $row['image_file']
        );

        array_push($events_array, $event);
    }

    if (count($events_array) > 0) {
        echo json_encode(["status" => "success", "data" => $events_array]);
    } else {
        echo json_encode(["status" => "error", "message" => "No upcoming events found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve upcoming events."]);
}

?>