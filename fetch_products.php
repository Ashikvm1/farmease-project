<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "FarmEase"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the category from the query string
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Prepare the SQL query
if ($category == 'All') {
    $sql = "SELECT * FROM products"; 
} else {
    $sql = "SELECT * FROM products WHERE category = ?";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($category != 'All') {
    $stmt->bind_param("s", $category);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch products
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($products);

// Close the connection
$stmt->close();
$conn->close();
?>
