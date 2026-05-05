<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Method: POST');
session_start();

require_once('../../../config/config.php');

if (isset($_SESSION['guide_id'])) {
    $guide_id = $_SESSION['guide_id'];

    // here pag nag sign out ka while yung status mo is 'Online', ise-set siya to 'Offline' unlike sa iba na kapag on tour ka or queuing or clocked in di magiging offline status mo makkep pa rin as is
    $sql = "UPDATE tour_guides SET current_status = 'Offline' WHERE guide_id = ? AND current_status = 'Online'";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $guide_id);
        mysqli_stmt_execute($stmt);
    }
}

// 3. Burahin ang session
session_unset();
session_destroy();

echo json_encode(["status" => "success", "message" => "Logged out successfully."]);
?>