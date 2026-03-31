<?php
include 'db_connect.php';

if(isset($_GET['reservation_id'])) {
    
    $reservation_id = $_GET['reservation_id'];

    $sql = "UPDATE RESERVATIONS
            SET status = 'Approve'
            WHERE reservation_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);

    if($stmt->execute()) {
        echo "Booking Approved Successfully!";
    }
    else {
        echo "Error Approving Booking.";
    }

    $stmt->close();
}

$conn->close();
?>