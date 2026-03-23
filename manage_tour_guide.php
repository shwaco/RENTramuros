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
        
        <table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Email</th>
      <th scope="col">Status</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
                <?php
                // 2. Fetch the data from the database
                $sql = "SELECT guide_id, first_name, last_name, email, is_verified FROM Tour_Guides";
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    // 3. Loop through the database and create a table row for each guide
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Check if they completed the OTP
                        $status_badge = $row['is_verified'] == 1 
                                        ? "<span class='badge bg-success'>Verified</span>" 
                                        : "<span class='badge bg-warning text-dark'>Pending OTP</span>";

                        echo "<tr>
                                <td>{$row['guide_id']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$status_badge}</td>
                                <td>
                                    <a href='edit_guide.php?updateid={$row['guide_id']}' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='delete_guide.php?deleteid={$row['guide_id']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No tour guides found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <a href="signup_tour_guide.php" class="btn btn-success">Add New Tour Guide</a>
    </div>
    <div class="button"></div>
</body>
</html>