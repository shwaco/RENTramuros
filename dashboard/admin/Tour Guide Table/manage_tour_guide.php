<?php
// 1. Connect to your database
include 'connect_phpmyadmin.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tour Guides</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tour Guides Directory (CRUD)</h2>
        
<table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Queue Pos</th>
              <th scope="col">First Name</th>
              <th scope="col">Last Name</th>
              <th scope="col">Email & Auth</th>
              <th scope="col">Last Finished Tour</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody class="table-group-divider">
                <?php
                // 2. Fetch the data from the database, sorted by oldest time first
                $sql = "SELECT guide_id, first_name, last_name, email, is_verified, current_status, last_dispatch_time 
                        FROM Tour_Guides 
                        ORDER BY last_dispatch_time ASC";
                
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    
                    $pos = 1; // Start our queue counter at 1
                    
                    // 3. Loop through the database and create a table row for each guide
                    while ($row = mysqli_fetch_assoc($result)) {
                        
                        // Check if they completed the OTP
                        $otp_badge = $row['is_verified'] == 1 
                                        ? "<span class='badge bg-success'>Verified</span>" 
                                        : "<span class='badge bg-warning text-dark'>Pending OTP</span>";

                        // Check their Queue Status
                        $queue_badge = $row['current_status'] == 'Available' 
                                        ? "<span class='badge bg-info text-dark'>Available</span>" 
                                        : "<span class='badge bg-secondary'>On Tour</span>";

                        // Highlight the row green if they are #1 in line AND Available
                        $row_class = ($pos == 1 && $row['current_status'] == 'Available') ? 'table-success' : '';

                        // Print all 8 columns perfectly matched to the headers
                        echo "<tr class='$row_class'>
                                <td>{$row['guide_id']}</td>
                                <td><strong>{$pos}</strong></td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['email']} <br> {$otp_badge}</td>
                                <td>{$row['last_dispatch_time']}</td>
                                <td>{$queue_badge}</td>
                                <td>
                                    <a href='edit_guide.php?updateid={$row['guide_id']}' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='delete_guide.php?deleteid={$row['guide_id']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                              </tr>";
                              
                        $pos++; // Increase the queue number for the next person
                    }
                } else {
                    // Update colspan to 8 to match the headers
                    echo "<tr><td colspan='8' class='text-center'>No tour guides found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <a href="signup_tour_guide.php" class="btn btn-success">Add New Tour Guide</a>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>