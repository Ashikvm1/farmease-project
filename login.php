<?php
session_start();
include 'db_connection.php'; // Database connection

// If already logged in, redirect accordingly

// Check if login form was submitted
if (isset($_POST['login'])) {
    $user_type = $_POST['user_type'];  // user_type (admin or user)
    $email = $_POST['user_email'];     // Email entered in the form
    $password = $_POST['password'];    // Password entered in the form

    // Determine the table and email column based on user type
    if ($user_type === 'admin') {  // Ensure strict comparison (===)
        $table = 'admin';
        $email_column = 'admin_email';
    } else {
        $table = 'user';
        $email_column = 'user_email';
    }

    // Prepare the query
    $query = "SELECT * FROM `$table` WHERE `$email_column` = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("<div class='alert alert-danger'>Query Error: " . $conn->error . "</div>");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Get user data

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_email'] = $user[$email_column];
            $_SESSION['user_type'] = $user_type; // Ensure the correct value is stored
            $_SESSION['user_name'] = $user['user_first_name'];
            $_SESSION['user_id'] = $user['id'];

            // ✅ **Redirect Based on User Type**
            if ($_SESSION['user_type'] === 'admin') {
                header("Location: admin.php");
                exit();
            } elseif ($_SESSION['user_type'] === 'user') {
                header("Location: home.html");
                exit();
            }
        } else {
            echo "<div class='alert alert-danger'>Incorrect Password!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email not found!</div>";
    }
}
?>
