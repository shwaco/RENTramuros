<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$error_msg = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'asset/connect_phpmyadmin.php';
    $email = $_POST['email'];

    // 1. Check if the email exists AND is currently unverified
    $sql = "SELECT * FROM `tourists` WHERE email='$email' AND is_verified=0";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $first_name = $row['first_name'];

        // 2. Generate a NEW OTP
        $new_otp = rand(100000, 999999);

        // 3. Update the database with the new OTP
        $update_sql = "UPDATE `tourists` SET otp='$new_otp' WHERE email='$email'";
        mysqli_query($con, $update_sql);

        // 4. Send the new email via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'RENTramuros@gmail.com';     // UPDATE THIS
            $mail->Password   = 'yaue vvhh imjw dnnd';    // UPDATE THIS
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port       = 465;

            $mail->setFrom('RENTramuros@gmail.com', 'RENTramuros System');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your NEW RENTramuros Verification Code';
            $mail->Body    = "Hello $first_name,<br><br>You requested a new verification code. Your new code is: <b>$new_otp</b><br><br>Please enter this code to verify your account.";

            $mail->send();
            
            // 5. Send them back to the verification page
            $_SESSION['temp_email'] = $email;
            header("location: auth/otp verification/otp_verification_tourist.php");
            exit();

        } catch (Exception $e) {
            $error_msg = "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        $error_msg = "No unverified account found with that email address.";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Resend OTP</title>
  </head>
  <body>

    <div class="container mt-5" style="max-width: 500px;">
        <h2 class="text-center mb-4">Resend Verification Code</h2>
        
        <?php
        if($error_msg) {
            echo "<div class='alert alert-danger'>$error_msg</div>";
        }
        ?>

        <form action="auth/resend otp/resend_otp_tourist.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Enter your registered email address</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <button type="submit" class="btn btn-warning w-100">Send New Code</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="auth/log in/login_tourist.php" class="text-decoration-none">Back to Login</a>
        </div>
    </div>

  </body>
</html>