<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../../backend.v2/config/config.php';
require_once '../../../backend.v2/config/mailer_config.php';

/** @var mysqli $con */
/** @var PHPMailer $mail */

// ✅ Fix #2: Read from $_POST, not php://input
$firstname = trim($_POST['first_name']     ?? '');
$lastname  = trim($_POST['last_name']      ?? '');
$email     = trim($_POST['email']          ?? '');
$password  = trim($_POST['password_hash']  ?? '');
$phone     = trim($_POST['phone_number']   ?? '');

if (!$firstname || !$lastname || !$email || !$password || !$phone) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit();
}

// Check if email already exists
$sql = "SELECT tourist_id, otp_code, otp_expiry FROM tourists WHERE email = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists. Please use a different email."]);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ✅ Fix #3: Save OTP to the database
$otp         = rand(100000, 999999);
$expiry_time = date("Y-m-d H:i:s", strtotime("+15 minutes"));

$sql  = "INSERT INTO tourists (first_name, last_name, email, password_hash, phone_number, otp_code, otp_expiry)
         VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sssssss", $firstname, $lastname, $email, $hashed_password, $phone, $otp, $expiry_time);

if (mysqli_stmt_execute($stmt)) {
    try {
        $mail->addAddress($email, $firstname . ' ' . $lastname);
        $mail->Subject = "Verification Code for RENTramuros Account";
        $mail->Body = "
    <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
        <h2>Welcome to RENTramuros, $firstname!</h2>
        <p>Your 6-digit verification code is: 
           <b style='font-size: 24px; color: #d32f2f;'>$otp</b>
        </p>
        <p>This code will expire in 15 minutes.</p>
        <hr>
        <p style='font-size: 12px; color: #777;'>
            If you did not request this, please ignore this email.
        </p>
    </div>";
        $mail->send();

        echo json_encode(["status" => "success", "message" => "Signup successful! Check your email for the OTP."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Signed up, but email failed: " . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Signup failed. Please try again."]);
}
?>