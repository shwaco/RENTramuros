<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Require the PHPMailer files you downloaded
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$success=0;
$email_address_error=0;

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'connect_phpmyadmin.php';
    $email=$_POST['email'];
    $password=$_POST['password'];
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $phone_number=$_POST['phone_number'];

    // Securely hash the password
    $hashed_password=password_hash($password, PASSWORD_DEFAULT);

    // Insert the hashed password instead of the raw one
    // $sql="INSERT INTO `system_users` (`email`, `password_hash`, `role`, `first_name`, `last_name`) VALUES ('$email', '$hashed_password', '$role', '$first_name', '$last_name')";
    // $result=mysqli_query($con, $sql);

    // if($result){
    //     echo "Admin registered successfully!";
    // } else {
    //     die (mysqli_error($con));
    // }

    $sql="select * from `tourists` where email='$email'";

    $result=mysqli_query($con, $sql);
    if($result) {
        $num=mysqli_num_rows($result);
        if($num>0) {
            // echo "User with this email already exists!";
            $email_address_error=1;
        }else{
            // --- NEW OTP & EMAIL LOGIC STARTS HERE ---
            
            // 1. Generate the OTP
            $otp = rand(100000, 999999);

            // 2. Insert user with OTP and is_verified = 0
            $sql="INSERT INTO `tourists` (`email`, `password_hash`,  `first_name`, `last_name`, `phone_number`, `otp`, `is_verified`) 
                  VALUES ('$email', '$hashed_password', '$first_name', '$last_name', '$phone_number', '$otp', 0)";
            $result=mysqli_query($con, $sql);

            if($result){
                
                // 3. Setup PHPMailer to send the code
                $mail = new PHPMailer(true);

                try {
                    // Server settings for Gmail
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'RENTramuros@gmail.com';     // Put your actual Gmail address here
                    $mail->Password   = 'yaue vvhh imjw dnnd';    // Put your 16-letter App Password here
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
                    $mail->Port       = 465;

                    // Recipients
                    $mail->setFrom('RENTramuros@gmail.com', 'RENTramuros System');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your RENTramuros Verification Code';
                    $mail->Body    = "Hello $first_name,<br><br>Your verification code is: <b>$otp</b><br><br>Please enter this code to complete your registration.";

                    $mail->send();
                    
                    // 4. Temporarily save the email in a session and redirect to the OTP page
                    session_start();
                    $_SESSION['temp_email'] = $email;
                    header("location: otp_verification_tourist.php");
                    exit();

                } catch (Exception $e) {
                    echo "Registration successful, but OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

            } else {
                die (mysqli_error($con));
            }
            // --- NEW OTP & EMAIL LOGIC ENDS HERE ---
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
  
    <title>Tourist Signup</title>
  </head>
  <body>

  <?php
if($email_address_error) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> User with this email already exists!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if($success) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong>Sign-up successful!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  header("Location: login_tourist.php");
}
?>
    <h1 class="text-center">Sign-up Page (Tourist)</h1>
    <div class="container mt-5">
        <form action="signup_tourist.php" method="POST">
   <div class="mb-3">
    <label for="exampleInputFirst_Name1" class="form-label">First Name</label>
    <input type="text" class="form-control" placeholder="Enter your first name" name = "first_name">
  </div>
   <div class="mb-3">
    <label for="exampleInputLast_Name1" class="form-label">Last Name</label>
    <input type="text" class="form-control" placeholder="Enter your last name" name = "last_name">
  </div>
  <div class="mb-3">
    <label for="exampleInputPhone_Number1" class="form-label">Phone Number</label>
    <input type="text" class="form-control" placeholder="Enter your phone number" name = "phone_number">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" class="form-control" placeholder="Enter your email" name = "email">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" placeholder="Enter your password" name = "password">
  </div>
 
  <button type="submit" class="btn btn-primary w-100">Sign-up</button>
</form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>