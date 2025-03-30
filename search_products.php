<?php
$servername = "localhost";
$username = "root";  // Change if necessary
$password = "";  // Change if necessary
$dbname = "FarmEase";  // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT id, name, category, farmer_name, price, product_image FROM products WHERE name LIKE '%$query%'";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();
?>
