<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php'; // Database connection

if (isset($_POST['register'])) {
    // Get user input
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['user_email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check_query = "SELECT user_email FROM user WHERE user_email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered! Please use another email.'); window.location='register.html';</script>";
        exit;
    }

    // Insert new user into database
    $query = "INSERT INTO user (user_first_name, user_last_name, user_email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Redirecting to login...'); window.location='index.html';</script>";
    } else {
        echo "<script>alert('Error! Registration failed.');</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
