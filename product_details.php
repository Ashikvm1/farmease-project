<?php
// Include your database connection
include('db_connection.php');

// Get the product ID from the query string
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Check if product ID is valid
if ($product_id <= 0) {
    echo json_encode([]);
    exit;
}

// Prepare the SQL query to fetch the product details
$sql = "SELECT name, product_image, category, farmer_name, phone_number, farmer_email, stock, price FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the product exists
if ($result->num_rows > 0) {
    // Fetch the product details
    $product = $result->fetch_assoc();
    $_SESSION['product_price'] = $product['price'];

    // Calculate the initial price based on default quantity (1 kg or 1 hour)
    $product['initial_price'] = number_format($product['price'], 2);
    echo json_encode([$product]);  // Return the product details as a JSON response
} else {
    // Return an empty array if no product found
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>
