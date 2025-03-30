<?php
// Include the database connection
include('db_connection.php');

// Get the incoming data
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['productId'];
$quantity = $data['quantity'];

// Check if the product exists and if enough stock is available
$sql = "SELECT stock FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $current_stock = $product['stock'];

    if ($current_stock >= $quantity) {
        // Update the stock
        $new_stock = $current_stock - $quantity;
        $update_sql = "UPDATE products SET stock = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_stock, $product_id);

        if ($update_stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Not enough stock']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}

$stmt->close();
$conn->close();
?>
