
<?php
$to = "recipient@example.com";
$subject = "Test Email with STARTTLS";
$message = "This is a test email sent from XAMPP using STARTTLS!";
$headers = "From: snehaadhikari2059@gmail.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>
