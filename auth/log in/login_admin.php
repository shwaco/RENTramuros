<?php

$login=0;
$invalid=0;
$unverified=0;

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'connect_phpmyadmin.php'; // Make sure this file has no echo statements!
    $email=$_POST['email'];
    $password=$_POST['password'];

    $sql="select * from `admins` where email='$email'";
    $result=mysqli_query($con, $sql);

    if($result) {
        $num=mysqli_num_rows($result);
        if($num>0) {
            $row=mysqli_fetch_assoc($result);
            
            // Check BOTH password and role against the database
            if(password_verify($password, $row['password_hash'])) {

              if($row['is_verified']==1) {
                
                // Start the session since credentials are correct
                session_start();
                $_SESSION['email']=$email;
                header("location: admin_dashboard.php");
                header("location: tour_guide_dashboard.php");
                exit(); // Stops script execution after redirect

            } else {
                // Password was wrong, OR the role typed didn't match their actual DB role
                $unverified=1;
            }
        } else {
            // No user found with that email
            $invalid=1;
        } 
      } else {
      $invalid=1;
    }
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  
    <title>Admin Login</title>
  </head>
  <body>

  <?php
if($unverified) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Hold on!</strong> You need to verify your email address before logging in. 
    <a href="resend_otp.php" class="alert-link">Click here to request a new code.</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
  if($login) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong>Login successful!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if($invalid) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> Invalid credentials!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
?>
    <h1 class="text-center">Login to our website (Admin)</h1>
    <div class="container mt-5">
        <form action="login_admin.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" class="form-control" placeholder="Enter your email" name = "email">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" placeholder="Enter your password" name = "password">
  </div>
  <button type="submit" class="btn btn-primary w-100">Login</button>
  <button type="button" class="btn btn-link w-100" onclick="window.location.href='signup_admin.php'">Don't have an account? Sign up here.</button>
</form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>