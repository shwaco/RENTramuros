<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

ob_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/../../../backend.v2/config/config.php'; // ✅ Bug 2 fixed
/** @var mysqli $con */

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->email) || !isset($data->otp)) {
    echo json_encode(["status" => "error", "message" => "Missing email or OTP."]);
    exit();
}

$email = $data->email;
$otp   = $data->otp;

$sql  = "SELECT tourist_id, otp_code, otp_expiry FROM tourists WHERE email = ?"; // ✅ Bug 3 fixed
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ((string)$row['otp_code'] !== (string)$otp) {
        echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
        exit();
    }

    if (date("Y-m-d H:i:s") > $row['otp_expiry']) {
        echo json_encode(["status" => "error", "message" => "OTP has expired."]);
        exit();
    }

    // ✅ Bug 4 fixed — set is_verified = 1
    $update_sql  = "UPDATE tourists SET otp_code = NULL, otp_expiry = NULL, is_verified = 1 WHERE tourist_id = ?";
    $update_stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $row['tourist_id']); // ✅ Bug 3 fixed
    if (mysqli_stmt_execute($update_stmt)) {
        echo json_encode(["status" => "success", "message" => "OTP verified successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating OTP status."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email not found."]);
    exit();
}
?>