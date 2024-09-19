<?php
if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'users_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check verification code
    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_code = ?");
    $stmt->bind_param("s", $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Mark email as verified
        $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_code = NULL WHERE id = ?");
        $stmt->bind_param("i", $row['id']);

        if ($stmt->execute()) {
            echo "Email verification successful!";
        } else {
            echo "Error verifying email.";
        }
    } else {
        echo "Invalid verification code.";
    }

    $stmt->close();
    $conn->close();
}
?>
