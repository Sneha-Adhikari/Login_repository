<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send]"])) 
{
    $mail =new PHPMailer (true);
    $mail->Host ='smtp.gmail.com';
    $mail->SMTP = true;
    $mail->Username ='snehaadhikari2059@gmail.com';
    $mail->Password ='xfso qnvo penq figj';
    $mail->SMTPSecure ='ssl';
    $mail->Port ='587';  
    
    $mail->setFrom('snehaadhikari2059@gmail.com');
    $mail->addAddress($_POST["email"]);
    $mail->isHTML (true);
    $mail->Subject = $_POST["subject"];
    $mail->Body = $_POST["message"];
    
    $mail->send();
    
    echo
    "
    <script>
    alert('Sent Successfully');
    document.location.href ='index.php';
    </script>
}

