<?php
// session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../asset/config.php';

// if ($_SESSION['tourist_id'] ?? null) {
//     $data = json_decode(file_get_contents("php://input"));
//     $data->tourist_id = $_SESSION['tourist_id'];
// } else {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed. Use POST."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->tourist_id)){
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing Tourist ID."]);
    exit();
}

$tourist_id = $data->tourist_id;
$status = $data->status ?? "Pending";
$adults_and_seniors = $data->adults_and_seniors ?? 0;
$children = $data->children ?? 0;
$infants = $data->infants ?? 0;
$booking_type = $data->booking_type;
$number_of_vehicle = $data->number_of_vehicle ?? 0;

$assigned_vehicle_id = $data->vehicle_id ?? null;
$assigned_guide_id = null;
$contact_info_id = $data->contact_info_id ?? null;

$package_id = $data->package_id ?? null;

$request_attraction = $data->request_attractions ?? [];

$full_name = $data->full_name ?? null;
$email_address = $data->email ?? null;
$phone_number = $data->phone_number ?? null;

mysqli_begin_transaction($con);

try{
    if ($full_name && $email_address && $phone_number) {
    $contact_insert_sql = "INSERT INTO contact_information (full_name, email_address, phone_number) VALUES (?, ?, ?)";
    $contact_insert_stmt = mysqli_prepare($con, $contact_insert_sql);
    mysqli_stmt_bind_param($contact_insert_stmt, "sss", $full_name, $email_address, $phone_number);

    if(!mysqli_stmt_execute($contact_insert_stmt)) {
        throw new Exception("Failed to insert contact information.");
        }
        $contact_info_id = mysqli_insert_id($con);
    }
    $booking_insert_sql = "INSERT INTO booking_history (tourist_id, status, adults_and_seniors, children, infants, booking_type, contact_info_id, number_of_vehicle, vehicle_id, guide_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $booking_insert_stmt = mysqli_prepare($con, $booking_insert_sql);
    mysqli_stmt_bind_param($booking_insert_stmt, "isiiisiiii", $tourist_id, $status, $adults_and_seniors, $children, $infants, $booking_type, $contact_info_id, $number_of_vehicle, $assigned_vehicle_id, $assigned_guide_id);

    if(!mysqli_stmt_execute($booking_insert_stmt)) {
        throw new Exception("Failed to create booking request.");
    }
    $booking_request_id = mysqli_insert_id($con);

    if (!empty($request_attraction) && is_array($request_attraction)) {
        $attraction_insert_sql = "INSERT INTO request_attractions (booking_request_id, attraction_id) VALUES (?, ?)";
        $attraction_insert_stmt = mysqli_prepare($con, $attraction_insert_sql);

        foreach ($request_attraction as $single_attraction_id) {
            mysqli_stmt_bind_param($attraction_insert_stmt, "ii", $booking_request_id, $single_attraction_id);
            if(!mysqli_stmt_execute($attraction_insert_stmt)) {
                throw new Exception("Failed to associate attractions with booking request.");
            }
        }
    }

    if($booking_type === "Package") {
        $package_insert_sql = "INSERT INTO booking_history (package_id) VALUES (?)";
        $package_insert_stmt = mysqli_prepare($con, $package_insert_sql);
        mysqli_stmt_bind_param($package_insert_stmt, "i", $booking_request_id);

        if(!mysqli_stmt_execute($package_insert_stmt)) {
            throw new Exception("Failed to create package booking.");
        }
    }

    mysqli_commit($con);
    http_response_code(201);
    echo json_encode(["status" => "success", "message" => "Booking request created successfully."]);

} catch (Exception $e) {
    mysqli_rollback($con);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);

}
?>