<?php
include 'connect_phpmyadmin.php';

if (isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    $sql = "DELETE FROM Vehicles WHERE vehicle_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Try to delete it
    try {
        if (mysqli_stmt_execute($stmt)) {
            // Success! Send them straight back to the table
            header("Location: manage_vehicle.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // SAFETY CATCH: If the vehicle is already attached to a past booking in your database, 
        // MySQL will block the deletion to protect your financial records.
        echo "<script>
                alert('Cannot delete this vehicle because it is tied to an existing customer booking. Change its status to \"Under Maintenance\" instead.');
                window.location.href = 'manage_vehicle.php';
              </script>";
    }
}
?>