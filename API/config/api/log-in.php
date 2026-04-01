<?php

session_start();

require_once "../config/database.php";
require_once "../models/Tourist.php";

$db = (new Database())->connect();
$tourist = new Tourist($db); 

$data = json_decode(file_get_contents("php://input"), true);

$tourist->email = $data['email'];
$password = $data['password'];

$stmt = $tourist->login();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user) {

    if(!password_verify($password,$user['password_hash'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid Password!"
        ]);

        exit();
    }
    if($user['is_verified'] == 0) {

        echo json_encode([
            "status" => "error",
            "message" => "Account Not Verified. \n Please Verify via OTP."
        ]);

        exit();
    }
    
    $_SESSION['tourist_id'] = $user['customer_id'];

    echo json_encode([
        "status" => "success",
        "message" => "Login Successful",
        "tourist_id" => $user['customer_id']
    ]);
}
else {

    echo json_encode([
        "status" => "error",
        "message" => "Email Not Found."
    ]);
}