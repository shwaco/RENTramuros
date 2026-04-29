<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../../../backend.v2/config/config.php';
require_once '../../../backend.v2/config/mailer_config.php';

/** @var mysqli $con */

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->email)) {
    echo json_encode(["status" => "error", "message" => "Please provide an email address."]);
    exit();
}

$email = $data->email;

// Changed $sql to $stmt here for cleaner code
$sql = "SELECT tourist_id, first_name, is_verified FROM tourists WHERE email = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row['is_verified']) {
        echo json_encode(["status" => "error", "message" => "This account is already verified. Please log in."]);
        exit();
    }

    $otp = rand(100000, 999999);
    $expiry_time = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    // FIX: Changed tourists_id to tourist_id
    $update_sql = "UPDATE tourists SET otp_code = ?, otp_expiry = ? WHERE tourist_id = ?";
    $update_stmt = mysqli_prepare($con, $update_sql);
    
    // PRO-TIP: Adding this check prevents 500 errors if you misspell a column again!
    if (!$update_stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($con)]);
        exit();
    }

    mysqli_stmt_bind_param($update_stmt, "ssi", $otp, $expiry_time, $row['tourist_id']);
    
    if (mysqli_stmt_execute($update_stmt)) {
        try {
            $mail->addAddress($email, $row['first_name']);

            $mail->Subject = "Your New OTP for RENTramuros Account";
            $mail->Body    = "<div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                                <h2>Hello, " . $row['first_name'] . "!</h2>
                                <p>Your new 6-digit verification code is: <b style='font-size: 24px; color: #d32f2f;'>$otp</b></p>
                                <p>This code will expire in exactly 15 minutes.</p>
                                <hr>
                                <p style='font-size: 12px; color: #777;'>If you did not request this code, please ignore this email.</p>
                            </div>";

            $mail->send();

            echo json_encode(["status" => "success", "message" => "A new OTP has been sent to your email. Please check your inbox."]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Failed to send OTP email. Please try again later."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update OTP. Please try again later."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email not found. Please check and try again."]);
}

?>