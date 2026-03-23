<!-- <?php
// 1. Connect to your database
include 'connect_phpmyadmin.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New Vehicle</h2>
        <form action="create_vehicle_process.php" method="POST">
            <div class="mb-3">
                <label for="vehicle_type" class="form-label">Vehicle Type</label>
                <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" required>
            </div>
            <div class="mb-3">
                <label for="passenger_capacity" class="form-label">Passenger Capacity</label>
                <input type="number" class="form-control" id="passenger_capacity" name="passenger_capacity" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Vehicle</button>
        </form>
        <a href="manage_vehicle.php" class="btn btn-secondary">Back to Vehicle List</a>
    </div>
</body>
</html> -->

<?php
// 1. Connect to the database
include 'connect_phpmyadmin.php';

$error_message = "";

// 2. Listen for the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Grab the vehicle type from the dropdown
    $vehicle_type = trim($_POST['vehicle_type']);
    
    // 3. Automatically assign the FIXED passenger capacity
    $passenger_capacity = 0;
    
    switch ($vehicle_type) {
        case "E-Tricycle":
            $passenger_capacity = 6;
            break;
        case "Kalesa":
            $passenger_capacity = 6; // Change this to your actual Kalesa capacity
            break;
        case "Tranvia":
            $passenger_capacity = 20; // Change this to your actual Tranvia capacity
            break;
        default:
            $error_message = "Invalid vehicle type selected.";
    }

    // 4. If a valid vehicle was selected, save it to the database
    if (empty($error_message)) {
        $sql = "INSERT INTO Vehicles (vehicle_type, passenger_capacity) VALUES (?, ?)";
        
        if ($stmt = mysqli_prepare($con, $sql)) {
            // "si" = String, Integer
            mysqli_stmt_bind_param($stmt, "si", $vehicle_type, $passenger_capacity);
            
            if (mysqli_stmt_execute($stmt)) {
                // Success! Send them back to the queue table
                header("Location: manage_vehicle.php");
                exit();
            } else {
                $error_message = "Error adding vehicle: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Database error: Could not prepare statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add New Vehicle</title>
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <h2 class="text-center mb-4">Add Vehicle to Fleet</h2>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="create_vehicle.php" method="POST">
                            
                            <div class="mb-4">
                                <label for="vehicle_type" class="form-label">Select Vehicle Type</label>
                                <select class="form-select" name="vehicle_type" id="vehicle_type" required>
                                    <option value="" disabled selected>Select a vehicle type...</option>
                                    <option value="E-Tricycle">E-Tricycle (6 pax)</option>
                                    <option value="Kalesa">Kalesa (6 pax)</option>
                                    <option value="Tranvia">Tranvia (20 pax)</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-2">Add to Fleet Queue</button>
                            <a href="manage_vehicles.php" class="btn btn-outline-secondary w-100">Cancel</a>
                            
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>