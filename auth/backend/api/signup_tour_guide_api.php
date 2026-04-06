<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Missing email or password."]);
    exit();
}

include './connect_phpmyadmin.php';
include '.../mailer_config.php';

$check_sql = "SELECT * FROM tour_guides WHERE email = ?";
$check_stmt = $mysqli->prepare($con, $check_sql);
mysqli_stmt_bind_param($check_stmt, "s", $data->email);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if(mysqli_stmt_num_rows($check_stmt) > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists."]);
    exit();
}

$hashed_password = password_hash($data->password, PASSWORD_DEFAULT);
$otp = rand(100000, 999999);

$insert_sql = "INSERT INTO tour_guides (first_name, last_name, email, password_hash, otp) VALUES (?, ?, ?, ?, ?)";
$insert_stmt = $mysqli->prepare($con, $insert_sql);
mysqli_stmt_bind_param($insert_stmt, "ssss", $data->firstname, $data->lastname, $data->email, $hashed_password, $otp);
if(mysqli_stmt_execute($insert_stmt)){
    $subject = "Tour Guide Account Created - OTP Verification";
    $body = "Your tour guide account has been created. Please use the following OTP to verify your email: $otp";
    $mail_sent = sendRentalEmail($data->email, $subject, $body);

    if($mail_sent) {
        echo json_encode(["status" => "success", "message" => "Tour guide account created successfully. Please check your email for the OTP to verify your account."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Tour guide account created, but failed to send OTP email. Please contact support."]);
    }
}
?>