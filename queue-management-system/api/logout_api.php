<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Method: POST');
session_start();

session_unset();
session_destroy();

echo json_encode(["status" => "success", "message" => "Logged out successfully."]);
?>