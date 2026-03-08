<?php

session_start();
if(!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role']!="Admin") {
    // User is not logged in or doesn't have the Admin role, redirect to login page
    header("location: login_system_user.php");
    exit();
}

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <h1 class="text-center text-warning mt-5">Admin Dashboard
    <?php
    echo $_SESSION['email']; // Display the logged-in admin's email
    ?>
    </h1>

    <div class="container mt-5">
       <a href="logout_system_user.php" class="btn btn-primary mt-5">Logout</a>
    </div>
        <!-- <div class="row">
            <div class="col-md-4">
                <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Manage System Users</div>
                    <div class="card-body">
                        <h5 class="card-title">Add, edit, or remove system users</h5>
                        <p class="card-text">Admins can manage all system users, including other admins, tour guides, and employees.</p>
                        <a href="signup_system_user.php" class="btn btn-light">Go to User Management</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">View Reports</div>
                    <div class="card-body">
                        <h5 class="card-title">View system reports and analytics</h5>
                        <p class="card-text">Admins can access various reports to monitor system performance and user activity.</p>
                        <a href="#" class="btn btn-light disabled">Go to Reports (Coming Soon)</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header">System Settings</div>
                    <div class="card-body">
                        <h5 class="card-title">Configure system settings and preferences</h5>
                        <p class="card-text">Admins can adjust system settings to optimize performance and user experience.</p>
                        <a href="#" class="btn btn-light disabled">Go to Settings (Coming Soon)</a>
                    </div>
                </div>
            </div>
        </div> -->
  </body>
</html>