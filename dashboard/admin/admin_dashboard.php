<?php

session_start();
if(!isset($_SESSION['email'])) {
    // User is not logged in or doesn't have the Admin role, redirect to login page
    header("location: auth/log in/login_admin.php");
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
    <link rel="stylesheet" href="styles.css">
</head>
  <body>
    <h1 class="text-center text-warning mt-5">Admin Dashboard
    <?php
    echo $_SESSION['email']; // Display the logged-in admin's email
    ?>
    </h1>
        <div class="row">

            <ul>
                <li><div class="col-md-4">

                        <div class="card text-bg-primary mb-3">
                            <div class="card-header">Manage Tour Guides</div>
                            
                            <div class="card-body">
                                <h5 class="card-title">Add, edit, or remove tour guides</h5>
                                <p class="card-text">Admins can manage all tour guides, including adding new ones, editing their information, and removing them if necessary.</p>
                                <a href="manage_tour_guide.php" class="btn btn-light">Go to Tour Guide Management</a>
                            </div>
                        </div>
                </div></li>

                <li><div class="col-md-4">
                    
                    <div class="card text-bg-success mb-3" >
                        <div class="card-header">Vehicle Queueing</div>

                        <div class="card-body">
                            <h5 class="card-title">View vehicle queueing information</h5>
                            <p class="card-text">Admins can access reports to monitor vehicle queueing and manage scheduling.</p>
                            <a href="manage_vehicle.php" class="btn btn-light">Go to Queueing Reports</a>
                        </div>
                    </div>
                </div></li>

                <li><div class="col-md-4">

                    <div class="card text-bg-warning mb-3" >
                        <div class="card-header">Tourist Spot Manager</div>
                        
                        <div class="card-body">
                            <h5 class="card-title">Manage tourist spots</h5>
                            <p class="card-text">Admins can add, edit, or remove tourist spots from the system.</p>
                            <a href="manage_tourist_spots.php" class="btn btn-light">Go to Tourist Spot Manager</a>
                        </div>
                    </div>
                </div></li>
            </ul>
        </div> 

        <div class="container mt-5">
       <a href="auth/log out/logout_admin.php" class="btn btn-primary mt-5">Logout</a></div> 
  </body>
</html>