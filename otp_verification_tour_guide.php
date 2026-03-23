<?php
session_start();

// If they try to access this page without signing up first, send them back
if(!isset($_SESSION['temp_email'])) {
    header("location: signup_tour_guide.php");
    exit();
}

$email = $_SESSION['temp_email'];
$invalid_otp = 0;
$success = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'connect_phpmyadmin.php';
    $entered_otp = $_POST['otp'];

    // Check if the OTP matches the one assigned to this email
    $sql = "SELECT * FROM `tour_guides` WHERE email='$email' AND otp='$entered_otp'";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) > 0) {
        // The OTP is correct! Update the database
        $update_sql = "UPDATE `tour_guides`SET is_verified=1, otp=NULL WHERE email='$email'";
        $update_result = mysqli_query($con, $update_sql);

        if($update_result) {
            $success = 1;
            // Clear the temporary email from the session
            unset($_SESSION['temp_email']); 
        } else {
            die(mysqli_error($con));
        }
    } else {
        // The OTP was wrong
        $invalid_otp = 1;
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Verify Account</title>
  </head>
  <body>

  <?php
    if($success) {
        echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <strong>Success!</strong> Your account is verified. You can now <a href="login_tour_guide.php" class="alert-link">Log In</a>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    if($invalid_otp) {
        echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <strong>Error!</strong> Invalid verification code. Please try again.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
  ?>

    <div class="container mt-5" style="max-width: 500px;">
        <h2 class="text-center mb-4">Enter Verification Code</h2>
        <p class="text-center text-muted">We sent a 6-digit code to <strong><?php echo $email; ?></strong></p>
        
        <form action="otp_verification_tour_guide.php" method="POST">
          <div class="mb-3">
            <input type="number" class="form-control form-control-lg text-center" placeholder="123456" name="otp" required>
          </div>
          <button type="submit" class="btn btn-primary w-100 btn-lg">Verify Account</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>