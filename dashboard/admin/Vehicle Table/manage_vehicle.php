<?php
// 1. Connect to your database
include 'asset/connect_phpmyadmin.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Vehicle Fleet Directory (Queue)</h2>
        
        <table class="table table-hover">
          <thead class="table-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Queue Pos</th>
              <th scope="col">Vehicle Type</th>
              <th scope="col">Passenger Capacity</th>
              <th scope="col">Last Dispatched</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody class="table-group-divider">
                <?php
                // 2. Fetch the data from the Vehicles table, sorted by oldest time first
                $sql = "SELECT vehicle_id, vehicle_type, passenger_capacity, current_status, last_dispatch_time 
                        FROM Vehicles 
                        ORDER BY last_dispatch_time ASC";
                
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    
                    $pos = 1; // Start our queue counter at 1
                    
                    // 3. Loop through the database and create a table row for each vehicle
                    while ($row = mysqli_fetch_assoc($result)) {
                        
                        // Check their Queue Status
                        $queue_badge = $row['current_status'] == 'Available' 
                                        ? "<span class='badge bg-info text-dark'>Available</span>" 
                                        : "<span class='badge bg-secondary'>On Tour</span>";

                        // Highlight the row green if it is #1 in line AND Available
                        $row_class = ($pos == 1 && $row['current_status'] == 'Available') ? 'table-success' : '';

                        echo "<tr class='$row_class'>
                                <td>{$row['vehicle_id']}</td>
                                <td><strong>{$pos}</strong></td>
                                <td>{$row['vehicle_type']}</td>
                                <td>{$row['passenger_capacity']} pax</td>
                                <td>{$row['last_dispatch_time']}</td>
                                <td>{$queue_badge}</td>
                                <td>
                                    <a href='dashboard/admin/Vehicle Table/edit_vehicle.php?updateid={$row['vehicle_id']}' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='dashboard/admin/Vehicle Table/delete_vehicle.php?deleteid={$row['vehicle_id']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                              </tr>";
                              
                        $pos++; // Increase the queue number for the next vehicle
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No vehicles found in the fleet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <a href="dashboard/admin/Vehicle Table/create_vehicle.php" class="btn btn-success">Add New Vehicle</a>
        <a href="dashboard/admin/admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>