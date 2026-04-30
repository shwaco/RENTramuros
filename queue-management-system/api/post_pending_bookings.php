<?php
header('Content-Type: application/json');
require_once('../../config/config.php');

// API para mag-insert ng walk-in tourist sa waiting queue.
// Inserts into contact_information first, then booking_history.
// tourist_id is NULL for walk-ins since they are not registered users.
$data = json_decode(file_get_contents('php://input'), true);

try {
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
        throw new Exception("Missing required tourist information.");
    }

    mysqli_begin_transaction($con);

    // 1. Insert contact info first
    $stmtC = mysqli_prepare($con, "INSERT INTO contact_information (first_name, last_name, email_address, phone_number) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmtC, "ssss",
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['phone_number']
    );
    if (!mysqli_stmt_execute($stmtC)) {
        throw new Exception("Could not save contact information.");
    }
    $contactInfoId = mysqli_insert_id($con);

    // 2. Get vehicle_id from vehicle_type if provided
    $vehicleId = null;
    if (!empty($data['service_type'])) {
        $stmtV = mysqli_prepare($con, "SELECT vehicle_id FROM vehicles WHERE vehicle_type = ? LIMIT 1");
        mysqli_stmt_bind_param($stmtV, "s", $data['service_type']);
        mysqli_stmt_execute($stmtV);
        $vehicleRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtV));
        if ($vehicleRow) $vehicleId = $vehicleRow['vehicle_id'];
    }

    // 3. Insert booking_history — tourist_id is NULL for walk-ins
    $bookingTime = date('H:i:s');
    $bookingDate = date('Y-m-d');
    $numberOfVehicle = isset($data['vehicle_count']) ? (int)$data['vehicle_count'] : 1;

    $stmtB = mysqli_prepare($con,
        "INSERT INTO booking_history 
            (tourist_id, booking_type, status, booking_time, booking_date, 
             adults_and_seniors, children, infants, contact_info_id, vehicle_id, number_of_vehicle)
         VALUES (NULL, 'Walk-in', 'Pending', ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $adults   = isset($data['adult_count'])    ? (int)$data['adult_count']    : 0;
    $children = isset($data['children_count']) ? (int)$data['children_count'] : 0;
    $infants  = isset($data['infant_count'])   ? (int)$data['infant_count']   : 0;

    mysqli_stmt_bind_param($stmtB, "ssiiiiiii",
        $bookingTime,
        $bookingDate,
        $adults,
        $children,
        $infants,
        $contactInfoId,
        $vehicleId,
        $numberOfVehicle
    );
    if (!mysqli_stmt_execute($stmtB)) {
        throw new Exception("Could not save booking.");
    }
    $bookingId = mysqli_insert_id($con);

    mysqli_commit($con);

    echo json_encode([
        'success'    => true,
        'message'    => 'Tour requested successfully! Please wait while we connect you with a tour guide.',
        'tourist_id' => $bookingId
    ]);

} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>