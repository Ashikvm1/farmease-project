<?php
session_start();

// Database Connection (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Prepare and execute the SQL statement
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  // Check if the user exists
  if ($result->num_rows > 0) {
    // Fetch the user data
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row["password"])) {
      // Set session variables
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["email"] = $row["email"];

      // Redirect to the home page or any other protected area
      header("Location: home.php");
      exit;
    } else {
      // Incorrect password
      $error = "Invalid email or password.";
    }
  } else {
    // User does not exist
    $error = "Invalid email or password.";
  }
}

// Close the database connection
$conn->close();

?>
