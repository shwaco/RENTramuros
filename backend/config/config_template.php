<?php
    $HOSTNAME = 'localhost';
    $USERNAME = 'fill_your_username';
    $PASSWORD = 'fill_your_password (if any)';
    $DATABASE_NAME = 'fill_your_database';

    $con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE_NAME);
    if(!$con) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Failed to connect to database."]);
        exit();
    }
?>