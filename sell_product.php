<?php
session_start();
// Database connection settings
$servername = "localhost"; // Change if your database server is different
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "FarmEase"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $farmer_name = $_POST['farmer_name'];
    $phone_number = $_POST['phone_number'];
    $farmer_email = $_POST['farmer_email'];
    $product_image = $_POST['product_image']; // Image URL input from user

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        die("Error: User not logged in.");
    }
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO products (name, price, category, stock, farmer_name, phone_number, farmer_email, product_image,farmer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssissi", $name, $price, $category, $stock, $farmer_name, $phone_number, $farmer_email, $product_image,$user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New product added successfully!";
        header("Location: home.html"); // Redirect to home page after successful submission
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
