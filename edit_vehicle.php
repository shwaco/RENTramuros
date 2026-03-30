<?php
include 'connect_phpmyadmin.php';
$error_message = "";

// 1. Get the ID from the URL (e.g., edit_vehicle.php?updateid=5)
$id = $_GET['updateid'];

// 2. Fetch the current data so we can pre-fill the form
$sql = "SELECT * FROM Vehicles WHERE vehicle_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// 3. Listen for the form submission to UPDATE the data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_type = $_POST['vehicle_type'];
    $new_status = $_POST['current_status'];
    
    // Automatically re-assign the fixed capacity just in case they changed the vehicle type
    $new_capacity = 0;
    switch ($new_type) {
        case "E-Tricycle": $new_capacity = 6; break;
        case "Kalesa": $new_capacity = 6; break;
        case "Tranvia": $new_capacity = 20; break;
    }

    // Update the database!
    $update_sql = "UPDATE Vehicles SET vehicle_type = ?, passenger_capacity = ?, current_status = ? WHERE vehicle_id = ?";
    $update_stmt = mysqli_prepare($con, $update_sql);
    
    // "sisi" = String, Integer, String, Integer
    mysqli_stmt_bind_param($update_stmt, "sisi", $new_type, $new_capacity, $new_status, $id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        header("Location: manage_vehicle.php");
        exit();
    } else {
        $error_message = "Error updating vehicle: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Vehicle</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Edit Vehicle #<?php echo $id; ?></h2>
                
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label">Vehicle Type</label>
                                <select class="form-select" name="vehicle_type" required>
                                    <option value="E-Tricycle" <?php if($row['vehicle_type'] == 'E-Tricycle') echo 'selected'; ?>>E-Tricycle (6 pax)</option>
                                    <option value="Kalesa" <?php if($row['vehicle_type'] == 'Kalesa') echo 'selected'; ?>>Kalesa (6 pax)</option>
                                    <option value="Tranvia" <?php if($row['vehicle_type'] == 'Tranvia') echo 'selected'; ?>>Tranvia (20 pax)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Current Status</label>
                                <select class="form-select" name="current_status" required>
                                    <option value="Available" <?php if($row['current_status'] == 'Available') echo 'selected'; ?>>Available (In Queue)</option>
                                    <option value="On Tour" <?php if($row['current_status'] == 'On Tour') echo 'selected'; ?>>On Tour</option>
                                    <option value="Under Maintenance" <?php if($row['current_status'] == 'Under Maintenance') echo 'selected'; ?>>Under Maintenance</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-2">Save Changes</button>
                            <a href="manage_vehicle.php" class="btn btn-outline-secondary w-100">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>