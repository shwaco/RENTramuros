<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

require_once '../config.php/config.php';

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->email) || !isset($data->otp)) {
    echo json_encode(["status" => "error", "message" => "Missing email or OTP."]);
    exit();
}

$email = $data->email;
$otp = $data->otp;

// Check if it's a tourist account
$sql = "SELECT customer_id, otp FROM tourists WHERE email = ?";
$stmt = $con->prepare($sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row['otp'] != $otp) {
        echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
        exit();
    }

    $update_sql = "UPDATE tourists SET is_verified = 1, otp = 0 WHERE customer_id = ?";
    $update_stmt = $con->prepare($update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $row['customer_id']);
    if (mysqli_stmt_execute($update_stmt)){
        echo json_encode(["status" => "success", "message" => "OTP verified successfully! You can now login."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating OTP status."]);
    }
    exit();
}

// Check if it's a tour guide account
$sql = "SELECT guide_id, otp FROM tour_guides WHERE email = ?";
$stmt = $con->prepare($sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row['otp'] != $otp) {
        echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
        exit();
    }

    $update_sql = "UPDATE tour_guides SET is_verified = 1, otp = 0 WHERE guide_id = ?";
    $update_stmt = $con->prepare($update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $row['guide_id']);
    if (mysqli_stmt_execute($update_stmt)){
        echo json_encode(["status" => "success", "message" => "OTP verified successfully! You can now login."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating OTP status."]);
    }
    exit();
}

echo json_encode(["status" => "error", "message" => "Email not found."]);

?>