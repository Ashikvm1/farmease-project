<?php
session_start();
// Include your database connection
include('db_connection.php');

$buyer_id = $_SESSION['user_id'];
$product_id = $_POST['id'];
$quantity = $_POST['quantity'];
$address = $_POST['address'];
$pin = $_POST['pin'];
$mobile = $_POST['mobile'];

// Fetch product details (farmer_id, price, stock, category)
$sql = "SELECT farmer_id, price, stock, category FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    $current_stock = $product['stock'];
    $farmer_id = $product['farmer_id'];
    $price = $product['price'];
    $category = strtolower($product['category']); // Convert to lowercase for consistency

    // Determine stock deduction logic
    if ($category == "tool") {
        $stock_to_deduct = 1;  // Always deduct 1 for tools
        $total_amount = NULL;   // ✅ Set total amount to NULL for tools
    } else {
        $stock_to_deduct = $quantity; // Deduct entered quantity for vegetables/fruits
        $total_amount = $price * $quantity; // ✅ Calculate total amount for non-tools
    }

    // Check if there is enough stock
    if ($current_stock >= $stock_to_deduct) {
        // Deduct stock
        $new_stock = $current_stock - $stock_to_deduct;
        $update_sql = "UPDATE products SET stock = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_stock, $product_id);
        $update_stmt->execute();

        // ✅ **Correct way to insert NULL into MySQL**
        if ($category == "tool") {
            $order_sql = "INSERT INTO orders (user_id, product_id, quantity, total_amount, address, pin, mobile_no, purchase_date) 
                          VALUES (?, ?, ?, NULL, ?, ?, ?, NOW())";
            $order_stmt = $conn->prepare($order_sql);
            $order_stmt->bind_param("iiisss", $buyer_id, $product_id, $quantity, $address, $pin, $mobile);
        } else {
            $order_sql = "INSERT INTO orders (user_id, product_id, quantity, total_amount, address, pin, mobile_no, purchase_date) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $order_stmt = $conn->prepare($order_sql);
            $order_stmt->bind_param("iiidsss", $buyer_id, $product_id, $quantity, $total_amount, $address, $pin, $mobile);
        }

        // Execute the order insertion
        if ($order_stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert order']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}

// Close statements and connection
$stmt->close();
$order_stmt->close();
$conn->close();
?>
