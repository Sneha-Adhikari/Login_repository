<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die('Passwords do not match!');
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'users_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if token exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_code = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Hash new password
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password and clear the token
        $stmt = $conn->prepare("UPDATE users SET password = ?, verification_code = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $row['id']);

        if ($stmt->execute()) {
            echo "Password has been reset successfully!";
        } else {
            echo "Error resetting password.";
        }
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close();
    $conn->close();
}
?>
