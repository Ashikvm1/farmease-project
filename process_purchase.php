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

// Fetch product details (farmer_id, price, stock)
$sql = "SELECT farmer_id, price, stock FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    $current_stock = $product['stock'];
    
    $farmer_id = $product['farmer_id'];
    $price = $product['price'];
    // Check if there is enough stock
    if ($current_stock >= $quantity) {
        // Deduct the stock
        $total_amount = $price * $quantity;
        $new_stock = $current_stock - $quantity;
        $update_sql = "UPDATE products SET stock = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_stock, $product_id);
        $update_stmt->execute();
        $order_sql = "INSERT INTO orders (user_id, product_id, quantity, total_amount, address, pin, mobile_no, purchase_date) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("iiidsss", $buyer_id, $product_id, $quantity, $total_amount, $address, $pin, $mobile);
        // Process the purchase (add to orders or do other processing)
        // For now, we just return success
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

$stmt->close();
$order_stmt->close();
$conn->close();
?>
