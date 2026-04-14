<?php
$login=0;
$invalid=0;
$unverified=0;

if($_SERVER["REQUEST_METHOD"]=="POST"){
    require_once('../../config/config.php'); 
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SECURE PREPARED STATEMENT
    $stmt = mysqli_prepare($con, "SELECT * FROM `tour_guides` WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result) {
        $num=mysqli_num_rows($result);
        if($num>0) {
            $row=mysqli_fetch_assoc($result);
            
            if(password_verify($password, $row['password_hash'])) {
              if($row['is_verified']==1) {

                  if ($row['current_status'] === 'Offline') {
                        $g_id = $row['guide_id'];
                        // Secure update statement
                        $update_stmt = mysqli_prepare($con, "UPDATE `tour_guides` SET current_status = 'Idle', became_available_at = NOW() WHERE guide_id = ?");
                        mysqli_stmt_bind_param($update_stmt, "i", $g_id);
                        mysqli_stmt_execute($update_stmt);
                  }
                    
                  session_start();
                  $_SESSION['email']=$email;
                  $_SESSION['guide_id']=$row['guide_id'];
                  header("location: ../../queue-management-system/index.php");
                  exit(); 

              } else {
                  $unverified=1;
              }
            } else {
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
  
    <title>Tour Guide Login</title>
  </head>
  <body>

  <?php
if($unverified) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Hold on!</strong> You need to verify your email address before logging in. 
    <a href="../resend otp/resend_otp_tour_guide.php" class="alert-link">Click here to request a new code.</a>
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
    <h1 class="text-center">Login to our website</h1>
    <div class="container mt-5">
        <form action="" method="POST">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" class="form-control" placeholder="Enter your email" name = "email">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" placeholder="Enter your password" name = "password">
  </div>
 
  <button type="submit" class="btn btn-primary w-100">Login</button>
  <button type="button" class="btn btn-link w-100" onclick="window.location.href='../sign up/signup_tour_guide.php'">Don't have an account? Sign up here.</button>
</form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>