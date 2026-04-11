<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

require_once '../../../asset/connect_phpmyadmin.php';

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->email) || !isset($data->otp)) {
    echo json_encode(["status" => "error", "message" => "Missing email or OTP."]);
    exit();
}

$email = $data->email;
$otp = $data->otp;

$sql = "SELECT tourists_id, otp_code, otp_expiry FROM tourists WHERE email = ?";
$stmt = $con->prepare($sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row['otp_code'] !== $otp) {
        echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
        exit();
    }

    $current_time = date("Y-m-d H:i:s");
    if ($current_time > $row['otp_expiry']) {
        echo json_encode(["status" => "error", "message" => "OTP has expired."]);
        exit();
    }

    $update_sql = "UPDATE tourists SET otp_code = NULL, otp_expiry = NULL WHERE tourists_id = ?";
    $update_stmt = $con->prepare($update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $row['tourists_id']);
    if  (mysqli_stmt_execute($update_stmt)){
        echo json_encode(["status" => "success", "message" => "OTP verified successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating OTP status."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email not found."]);
    exit();
}

?>