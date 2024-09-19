<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        die('Passwords do not match!');
    }

    // reCAPTCHA Validation
    $secretKey = '6LcGfUYqAAAAAMIqgFatSGHM6E39GUnOWgGKY5Mf';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse";
    $responseKeys = json_decode(file_get_contents($url), true);

    if (!$responseKeys['success']) {
        die('Please complete the CAPTCHA');
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'users_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die('Email already exists.');
    }

    // Generate verification code
    $verification_code = md5(rand());

    // Insert user into database
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $verification_code);

    if ($stmt->execute()) {
        // Send verification email
        $subject = "Email Verification";
        $message = "Click the link below to verify your email: http://localhost/dashboard/verify.php?code=$verification_code";
        $headers = "From: no-reply@yourdomain.com";
        mail($email, $subject, $message, $headers);
        
        echo "Registration successful! Please check your email to verify your account.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
