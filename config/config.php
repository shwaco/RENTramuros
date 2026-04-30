<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE_NAME = 'rentramuros_database';

$con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE_NAME);

if (!$con) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database Connection Failed: ' . mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($con, "utf8mb4");
?>