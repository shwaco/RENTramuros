<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/config.php';
/** @var mysqli $con */


$data = json_decode(file_get_contents("php://input"));
if(!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Please Enter email or password."]);
    exit();
}

$email = $data->email;
$password = $data->password;

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
    $_SESSION['user_id'] = $row['admin_id'];
    $_SESSION['role'] = 'admin';
    echo json_encode([
        "status" => "success", 
        "message" => "Login Successful as Admin!", 
        "role" => "admin",
    ]);
    exit();
}

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

    $_SESSION['user_id'] = $row['guide_id'];
    $_SESSION['role'] = 'guide';

    $update_sql = "UPDATE tour_guides SET current_status = 'Online', last_active_at = NOW() WHERE guide_id = ? AND current_status NOT IN ('On Tour')";
    $update_stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $row['guide_id']);
    mysqli_stmt_execute($update_stmt);

    echo json_encode([
        "status" => "success", 
        "message" => "Login Successful as Tour Guide!", 
        "role" => "guide", 
        ]);
    exit();
}

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
    $_SESSION['user_id'] = $row['tourist_id'];
    $_SESSION['role'] = 'tourist';
    echo json_encode([
        "status" => "success", 
        "message" => "Login Successful as Tourist!", 
        "role" => "tourist", 
        ]);
    exit();
}

echo json_encode(["status" => "error", "message" => "Email not found. Please sign up first."]);
?>