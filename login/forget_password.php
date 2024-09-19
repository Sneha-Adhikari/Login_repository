<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'users_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Generate a reset token
        $reset_token = md5(rand());

        // Store the reset token in the database
        $stmt = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
        $stmt->bind_param("ss", $reset_token, $email);
        if ($stmt->execute()) {
            // Send reset email
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password: http://localhost/dashboard/reset_password.php?token=$reset_token";
            $headers = "From: snehaadhikari2059@gmail.com";
            mail($email, $subject, $message, $headers);

            echo "Password reset link has been sent to your email!";
        } else {
            echo "Failed to generate reset token.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
