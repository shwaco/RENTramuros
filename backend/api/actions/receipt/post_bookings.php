<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once('../../../config/config.php');
require_once('../../../logics/alphanumeric_id_generator.php'); 
/** @var mysqli $con */

$data = json_decode(file_get_contents("php://input"));

//if ($_SESSION['tourist_id'] ?? null) {
//    $data->tourist_id = $_SESSION['tourist_id'];
//} else {
//   http_response_code(401);
//    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
//    exit();
//}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed."]);
    exit();
}

if(!isset($data->tourist_id)){
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing Tourist ID."]);
    exit();
}

$unique_id = generateBookingCode($con);

$tourist_id = $data->tourist_id;
$booking_type = $data->booking_type;
$status = $data->status ?? "Pending";
$date_of_request = $data->booking_date;
$time_of_request = $data->booking_time;
$adults_and_seniors = $data->adults_and_seniors ?? 0;
$children = $data->children ?? 0;
$infants = $data->infants ?? 0;
$number_of_vehicle = $data->number_of_vehicle ?? 0;

$assigned_vehicle_id = $data->vehicle_id ?? null;
$assigned_guide_id = null;
$contact_info_id = $data->contact_info_id ?? null;
$package_id = $data->package_id ?? null;
$attraction_id = $data->attraction_id ?? [];

$first_name = $data->first_name ?? null;
$last_name = $data->last_name ?? null;
$email_address = $data->email_address ?? null;
$phone_number = $data->phone_number ?? null;

$unique_id = generateBookingcode($con);

mysqli_begin_transaction($con);

try {
    if ($first_name && $last_name && $email_address && $phone_number) {
        $contact_sql = "INSERT INTO contact_information (first_name, last_name, email_address, phone_number) VALUES (?, ?, ?, ?)";
        $contact_stmt = mysqli_prepare($con, $contact_sql);
        mysqli_stmt_bind_param($contact_stmt, "ssss", $first_name, $last_name, $email_address, $phone_number);
        mysqli_stmt_execute($contact_stmt);
        $contact_info_id = mysqli_insert_id($con);
    }
    
    if($booking_type === 'Packages') {
        $sql = "INSERT INTO booking_history (unique_id, tourist_id, status, booking_time, booking_date, adults_and_seniors, children, infants, booking_type, package_id, contact_info_id, number_of_vehicle, vehicle_id, guide_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sisssiiisiiiii", $unique_id, $tourist_id, $status, $time_of_request, $date_of_request, $adults_and_seniors, $children, $infants, $booking_type, $package_id, $contact_info_id, $number_of_vehicle, $assigned_vehicle_id, $assigned_guide_id);
    } else {
        $sql = "INSERT INTO booking_history (unique_id, tourist_id, status, booking_time, booking_date, adults_and_seniors, children, infants, booking_type, contact_info_id, number_of_vehicle, vehicle_id, guide_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sisssiiisiiii", $unique_id, $tourist_id, $status, $time_of_request, $date_of_request, $adults_and_seniors, $children, $infants, $booking_type, $contact_info_id, $number_of_vehicle, $assigned_vehicle_id, $assigned_guide_id);
    }

    mysqli_stmt_execute($stmt);
    $booking_request_id = mysqli_insert_id($con);

    if($booking_type === 'Packages' && !empty($package_id)) {
        $pkg_sql = "INSERT INTO request_packages (booking_request_id, package_id) VALUES (?, ?)";
        $pkg_stmt = mysqli_prepare($con, $pkg_sql);
        mysqli_stmt_bind_param($pkg_stmt, "ii", $booking_request_id, $package_id);
        mysqli_stmt_execute($pkg_stmt);
    }
    
    if($booking_type === 'Attractions' && !empty($attraction_id)) {
        $attr_sql = "INSERT INTO request_attractions (booking_request_id, attraction_id) VALUES (?, ?)";
        $attr_stmt = mysqli_prepare($con, $attr_sql);
        foreach ($attraction_id as $single_id) {
            mysqli_stmt_bind_param($attr_stmt, "ii", $booking_request_id, $single_id);
            mysqli_stmt_execute($attr_stmt);
        }
    }

    mysqli_commit($con);
    http_response_code(201);
    echo json_encode([
        "status" => "success", 
        "message" => "Booking request created successfully.",
        "unique_id" => $unique_id
    ]);

} catch (Exception $e) {
    mysqli_rollback($con);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>