<?php

require_once "../config/database.php";
require_once "../models/Tourist.php";

$db = (new Database ())->connect();
$tourist = new Tourist($db);

$data = json_decode(file_get_contents("php://input"), true);

$tourist->first_name = $data['first_name'];
$tourist->last_name = $data['last_name'];
$tourist->email = $data['email'];
$tourist->password = $data['password'];
$tourist->phone_number = $data['phone_number'];

if($tourist->register()) {

    echo json_encode([
        "status" => "success",
        "message" => "Tourist Registered Successfully. \n OTP Sent For Verification."
    ]);
}
else {

    echo json_encode ([
        "status" => "failed",
        "message" => "Registration Failed."
    ]);
}