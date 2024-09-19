<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path if necessary

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com';                   
    $mail->SMTPAuth   = true;                               
    $mail->Username   = 'snehaadhikari2059@gmail.com'; // Replace with your email address
    $mail->Password   = 'xfso qnvo penq figj'; // Replace with your app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      
    $mail->Port       = 587;                               

    //Recipients
    $mail->setFrom('snehaadhikari2059@gmail.com', 'Sneha Adhikari'); // Replace with your email address
    $mail->addAddress('adhikariiisneha2059@gmail.com'); // Replace with recipient email

    // Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Email Verification';
    $mail->Body    = 'Please click the link to verify your email: <a href="http://your-domain.com/verify.php?code=' . $verification_code . '">Verify Email</a>';

    $mail->send();
    echo 'Verification email sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
