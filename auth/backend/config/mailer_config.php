<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '.../PHPMailer/src/Exception.php';
require '.../PHPMailer/src/PHPMailer.php';
require '.../PHPMailer/src/SMTP.php';

function sendRentalEmail($to_email, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'RENTramuros@gmail.com';
        $mail->Password   = 'yaue vvhh imjw dnnd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('RENTramuros@gmail.com', 'RENTramuros System');
        $mail->addAddress($to_email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }   
}
?>