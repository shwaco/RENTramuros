<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$db_name = "rentramuros_database";

$con = mysqli_connect($host, $username, $password, $db_name);

if (!$con) {
    header('Content-Type: applcication/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database Connection Failed: ' . mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($con, "utf8");
?>