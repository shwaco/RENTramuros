<?php
    $HOSTNAME = 'localhost';
    $USERNAME = 'root';
    $PASSWORD = '';
    $DATABASE_NAME = '';

    $con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE_NAME);
    if(!$con) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Failed to connect to database."]);
        exit();
    }
?>