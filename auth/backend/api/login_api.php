<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once  '../../../asset/config.php';
/** @var mysqli $con */

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Please Enter email or password."]);
    exit();
}

$email = $data->email;
$raw_password = $data->password;

$admin_sql = "SELECT * FROM admins WHERE email = ?";
$stmt = mysqli_prepare($con, $admin_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {

    if (!password_verify($raw_password, $row['password_hash'])) {
    echo json_encode(["status" => "error", "message" => "Invalid password."]);
    exit();
    }

    $_SESSION['admin_id'] = $row['admin_id'];
    echo json_encode(["status" => "success", "message" => "Login Successful as Admin!", "admin_id" => $row['admin_id']]);
    exit();
}

$guide_sql = "SELECT * FROM tour_guides WHERE email = ?";
$stmt = mysqli_prepare($con, $guide_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {

    if (!password_verify($raw_password, $row['password_hash'])) {
    echo json_encode(["status" => "error", "message" => "Invalid password."]);
    exit();
    }

    $_SESSION['guide_id'] = $row['guide_id'];
    echo json_encode(["status" => "success", "message" => "Login Successful as Tour Guide!", "guide_id" => $row['guide_id']]);
    exit();
}

$tourist_sql = "SELECT * FROM tourists WHERE email = ?";
$stmt = mysqli_prepare($con, $tourist_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {

    if (!password_verify($raw_password, $row['password_hash'])) {
    echo json_encode(["status" => "error", "message" => "Invalid password."]);
    exit();
    }

    if ($row['is_verified'] == 0) {
        echo json_encode(["status" => "error", "message" => "Account not verified. Please check your email for the OTP to verify your account."]);
        exit();
    }

    $_SESSION['tourist_id'] = $row['tourist_id'];
    echo json_encode(["status" => "success", "message" => "Login Successful as Tourist!", "tourist_id" => $row['tourist_id']]);
    exit();
} 

echo json_encode(["status" => "error", "message" => "Email not found. Please sign up first."]);
?>