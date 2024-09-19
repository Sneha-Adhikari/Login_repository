<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'users_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND email_verified = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Generate new verification code
        $verification_code = md5(rand());

        // Update verification code in the database
        $stmt = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
        $stmt->bind_param("ss", $verification_code, $email);

        if ($stmt->execute()) {
            // Send verification email
            $subject = "Resend Email Verification";
            $message = "Click the link below to verify your email: http://localhost/dashboard/verify.php?code=$verification_code";
            $headers = "From: snehaadhikari2059@gmail.com";
            mail($email, $subject, $message, $headers);

            echo "Verification email has been resent!";
        } else {
            echo "Error resending verification.";
        }
    } else {
        echo "Email not found or already verified.";
    }

    $stmt->close();
    $conn->close();
}
?>
