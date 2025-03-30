<?php
$servername = "localhost"; // Database host
$username = "root";        // Database username (change if necessary)
$password = "";            // Database password (change if necessary)
$dbname = "FarmEase";      // Database name (change to match your actual database name)

// Create a connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Improved error handling
}

// Set the character set to UTF-8 (good practice to avoid encoding issues)
$conn->set_charset("utf8");

?>
