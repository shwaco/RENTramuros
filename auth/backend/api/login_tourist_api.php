<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: charset=UTF-8');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once './connect_phpmyadmin.php';

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Please Enter email or password."]);
    exit();
}

$email = $data->email;
$password = $data->password;

$sql = "SELECT * FROM tourists WHERE email = ?";
$stmt = $con->prepare($sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {

    if (!password_verify($password, $row['password'])) {
        echo json_encode(["status" => "error", "message" => "Invalid password."]);
        exit();
    }

    if ($row['is_verified'] == 0) {
        echo json_encode(["status" => "error", "message" => "Account not verified. Please check your email for the OTP to verify your account."]);
        exit();
    }

    $_SESSION['tourist_id'] = $row['tourist_id'];
    echo json_encode(["status" => "success", "message" => "Login Successful!", "tourist_id" => $row['tourist_id']]);

}else{
    echo json_encode(["status" => "error", "message" => "Email not found. Please sign up first."]);
    exit();
}

?>