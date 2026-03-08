<?php

$success=0;
$email_address_error=0;

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'connect_phpmyadmin.php';
    $email=$_POST['email'];
    $password=$_POST['password'];
    $role=$_POST['role'];
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];

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

    $sql="select * from `system_users` where email='$email'";

    $result=mysqli_query($con, $sql);
    if($result) {
        $num=mysqli_num_rows($result);
        if($num>0) {
            // echo "User with this email already exists!";
            $email_address_error=1;
        }else{
            $sql="INSERT INTO `system_users` (`email`, `password_hash`, `role`, `first_name`, `last_name`) VALUES ('$email', '$hashed_password', '$role', '$first_name', '$last_name')";
            $result=mysqli_query($con, $sql);

            if($result){
                // echo "Sign-up successful!";
                $success=1;
            } else {
                die (mysqli_error($con));
            }
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
  
    <title>System User Signup</title>
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
  header("Location: login_system_user.php");
}
?>
    <h1 class="text-center">Sign-up Page (System User)</h1>
    <div class="container mt-5">
        <form action="signup_system_user.php" method="POST">
   <div class="mb-3">
    <label for="exampleInputRole1" class="form-label">Role</label>
    <input type="text" class="form-control" placeholder="Enter your role" name = "role">
  </div>
   <div class="mb-3">
    <label for="exampleInputFirst_Name1" class="form-label">First Name</label>
    <input type="text" class="form-control" placeholder="Enter your first name" name = "first_name">
  </div>
   <div class="mb-3">
    <label for="exampleInputLast_Name1" class="form-label">Last Name</label>
    <input type="text" class="form-control" placeholder="Enter your last name" name = "last_name">
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

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host='smtp.gmail.com';
    $mail->SMTPAuth=true;
    $mail->Username='your_gmail@gmail.com'; // Your Gmail
    $mail->Password='your_app_password'; // App Password
    $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port=587;

    // Recipients
    $mail->setFrom('your_gmail@gmail.com', 'Mailer');
    $mail->addAddress('recipient@example.com');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Email Verification';
    $mail->Body    = 'Click here to verify: <a href="http://yourwebsite.com/verify.php?token=123">Verify</a>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


?>