<?php

// session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../../asset/config.php';
require_once '../config/mailer_config.php';

// $_SESSION = array();

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->first_name) || !isset($data->last_name) || !isset($data->email) || !isset($data->password_hash) || !isset($data->phone_number)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit();
}

$firstname = $data->first_name;
$lastname = $data->last_name;
$email = $data->email;
$password = $data->password_hash;
$phone = $data->phone_number;

$sql = "SELECT * FROM tourists where email = ?";
$stmt = $con->prepare($sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists. Please use a different email."]);
    exit();
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$otp = rand(100000, 999999);
$expiry_time = date("Y-m-d H:i:s", strtotime("+15 minutes"));

$sql = "INSERT INTO tourists (first_name, last_name, email, password_hash, phone_number) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
mysqli_stmt_bind_param($stmt, "sssss", $firstname, $lastname, $email, $hashed_password, $phone);
if (mysqli_stmt_execute($stmt)) {

    try {
        
        $mail->addAddress($email, $firstname . ' ' . $lastname);

        $mail->Subject = "Verification Code for RENTramuros Account";
        $mail->Body    = "<div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                            <h2>Welcome to RENTramuros, $firstname!</h2>
                            <p>Your 6-digit verification code is: <b style='font-size: 24px; color: #d32f2f;'>$otp</b></p>
                            <p>This code will expire in exactly 15 minutes.</p>
                            <hr>
                            <p style='font-size: 12px; color: #777;'>If you did not request this account, please ignore this email.</p>
                        </div>";

        $mail->send();

        echo json_encode(["status" => "success", "message" => "Signup successful! Please check your email for the OTP to verify your account."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Signup successful, but failed to send verification email. Please contact support."]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Signup failed. Please try again later."]);
}


?>