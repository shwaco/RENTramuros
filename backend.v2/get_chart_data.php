<?php
// session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

require_once '../asset/config.php';
/** @var mysqli $con */

// Security Check (Uncomment this when you are ready to enforce login!)
/*
if(!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized."]);
    exit();
}
*/

$response_data = [
    "pie_chart" => ["labels" => [], "values" => []],
    "bar_chart" => ["labels" => [], "values" => []],
    "line_chart" => ["dates" => [], "packages" => [], "attractions" => []]
];

// ========================================================
// 1. PIE CHART DATA: Visits per Attraction
// Uses the junction table 'request_attractions'
// ========================================================
$pie_sql = "SELECT a.attraction_name, COUNT(ra.booking_request_id) AS total_visits 
            FROM request_attractions ra 
            JOIN attractions a ON ra.attraction_id = a.attraction_id 
            JOIN booking_history bh ON ra.booking_request_id = bh.booking_request_id
            GROUP BY a.attraction_id";

if ($result = mysqli_query($con, $pie_sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response_data['pie_chart']['labels'][] = $row['attraction_name'];
        $response_data['pie_chart']['values'][] = (int)$row['total_visits'];
    }
}

// ========================================================
// 2. BAR CHART DATA: Packages Availed
// Uses the direct 'package_id' inside 'booking_history'
// ========================================================
$bar_sql = "SELECT p.package_name, COUNT(bh.booking_request_id) AS total_booked 
            FROM booking_history bh 
            JOIN packages p ON bh.package_id = p.package_id 
            GROUP BY p.package_id";

if ($result = mysqli_query($con, $bar_sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response_data['bar_chart']['labels'][] = $row['package_name'];
        $response_data['bar_chart']['values'][] = (int)$row['total_booked'];
    }
}

// ========================================================
// 3. LINE CHART DATA: Booking Trends
// Groups by the 'booking_date' and 'booking_type'
// ========================================================
$line_sql = "SELECT booking_date, booking_type, COUNT(booking_request_id) as daily_count 
             FROM booking_history 
             GROUP BY booking_date, booking_type 
             ORDER BY booking_request_id DESC 
             LIMIT 14"; // Grabs the most recent dates

if ($result = mysqli_query($con, $line_sql)) {
    $temp_dates = [];
    $packages_data = [];
    $attractions_data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $date = $row['booking_date'];
        
        // I noticed booking_type is varchar, making sure we handle case sensitivity
        $type = strtolower($row['booking_type']); 
        $count = (int)$row['daily_count'];

        // Add date to our tracking array if it's new
        if (!in_array($date, $temp_dates)) {
            $temp_dates[] = $date;
            $packages_data[$date] = 0; // Initialize with 0
            $attractions_data[$date] = 0; // Initialize with 0
        }

        // Assign the count to the proper category
        if (strpos($type, 'package') !== false) {
            $packages_data[$date] = $count;
        } else {
            $attractions_data[$date] = $count;
        }
    }

    // Format for Chart.js
    // We use array_reverse so the oldest dates are on the left of the chart, newest on the right
    $response_data['line_chart']['dates'] = array_reverse(array_values($temp_dates));
    $response_data['line_chart']['packages'] = array_reverse(array_values($packages_data));
    $response_data['line_chart']['attractions'] = array_reverse(array_values($attractions_data));
}

// Send it all back to the frontend!
echo json_encode(["status" => "success", "data" => $response_data]);
?>