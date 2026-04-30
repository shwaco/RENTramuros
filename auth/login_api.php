<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

<<<<<<< HEAD
require_once '../config/config.php';
=======
require_once '../backend/config/config.php';
>>>>>>> ea24ad41558900a3169e1df362926e40b417f4bb
/** @var mysqli $con */

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Please Enter email or password."]);
    exit();
}

$email = $data->email;
$password = $data->password;

// 1. Check Admins
$admin_sql = "SELECT * FROM admins WHERE email = ?";
$stmt = mysqli_prepare($con, $admin_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (!password_verify($password, $row['password_hash'])) {
        echo json_encode(["status" => "error", "message" => "Invalid password."]);
        exit();
    }
    $_SESSION['admin_id'] = $row['admin_id'];
    echo json_encode(["status" => "success", "message" => "Login Successful as Admin!", "role" => "admin", "admin_id" => $row['admin_id']]);
    exit();
}

// 2. Check Tour Guides
$guide_sql = "SELECT * FROM tour_guides WHERE email = ?";
$stmt = mysqli_prepare($con, $guide_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (!password_verify($password, $row['password_hash'])) {
        echo json_encode(["status" => "error", "message" => "Invalid password."]);
        exit();
    }

    $_SESSION['guide_id'] = $row['guide_id'];

    // Set to Online if they were Offline or Available (new DB default is 'Available')
<<<<<<< HEAD
    $update_sql = "UPDATE tour_guides SET current_status = 'Online' WHERE guide_id = ? AND current_status IN ('Offline', 'Available')";
    $update_stmt = mysqli_prepare($con, $update_sql);
=======
    $update_stmt = mysqli_prepare($con, "UPDATE tour_guides SET current_status = 'Online' WHERE guide_id = ? AND current_status IN ('Offline', 'Available')");
>>>>>>> ea24ad41558900a3169e1df362926e40b417f4bb
    mysqli_stmt_bind_param($update_stmt, "i", $row['guide_id']);
    mysqli_stmt_execute($update_stmt);

    echo json_encode(["status" => "success", "message" => "Login Successful as Tour Guide!", "role" => "guide", "guide_id" => $row['guide_id']]);
    exit();
}

// 3. Check Tourists — uses tourist_id (not customer_id) and otp_code (not otp)
$tourist_sql = "SELECT * FROM tourists WHERE email = ?";
$stmt = mysqli_prepare($con, $tourist_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (!password_verify($password, $row['password_hash'])) {
        echo json_encode(["status" => "error", "message" => "Invalid password."]);
        exit();
    }
    if ($row['is_verified'] == 0) {
        echo json_encode(["status" => "unverified", "message" => "Account not verified. Please check your email for the OTP to verify your account."]);
        exit();
    }
    $_SESSION['tourist_id'] = $row['tourist_id'];
    echo json_encode(["status" => "success", "message" => "Login Successful as Tourist!", "role" => "tourist", "tourist_id" => $row['tourist_id']]);
    exit();
}

echo json_encode(["status" => "error", "message" => "Email not found. Please sign up first."]);
?>