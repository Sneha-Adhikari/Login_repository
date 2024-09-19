<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

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

    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            if ($row['email_verified'] == 1) {
                echo "Login successful! Welcome, " . $row['username'];
                // Start a session for the logged-in user
                $_SESSION['username'] = $row['username'];
            } else {
                echo "Please verify your email before logging in.";
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
