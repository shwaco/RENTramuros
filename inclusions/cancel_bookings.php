<?php
include_once('../connect_phpmyadmin.php');

if(isset($_GET['reservation_id'])) {
    
    $reservation_id = $_GET['reservation_id'];

    $sql = "UPDATE RESERVATIONS
            SET status = 'Cancelled'
            WHERE reservation_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);

    if($stmt->execute()) {
        echo "Booking Cancelled Successfully!";
    }
    else {
        echo "Error Cancelling Booking.";
    }

    $stmt->close();
}

$conn->close();
?>