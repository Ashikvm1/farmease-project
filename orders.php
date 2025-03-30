<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$farmer_id = $_SESSION['user_id']; // Logged-in farmer's ID

// Handle Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update the status in the database
    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $order_id);
    
    if ($update_stmt->execute()) {
        echo "<script>window.location.href='orders.php';</script>";
    } else {
        echo "<script>alert('Failed to update status!');</script>";
    }
    $update_stmt->close();
}

// Fetch orders where the product belongs to the logged-in farmer
$sql = "SELECT orders.id, user.user_first_name, user.user_last_name, 
               products.name AS product_name, orders.quantity, orders.total_amount, 
               orders.address, orders.pin, orders.mobile_no, orders.purchase_date, orders.status 
        FROM orders 
        JOIN user ON orders.user_id = user.id
        JOIN products ON orders.product_id = products.id
        WHERE products.farmer_id = ? 
        ORDER BY orders.purchase_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: white;">

<header>
    <div class="navbar2" style="background-color: #232f3e; height: 100px; color: white; display: flex; align-items: center; justify-content: center;">
        <h1 class="logo" style="margin: 0;">Manage Orders</h1>
    </div>
    <nav style="background-color:rgba(6, 157, 6, 0.99); padding: 10px; text-align: center;">
            <a href="home.html" style="color: white;">Home</a> |
            
            <a href="account.php" style="color: white;">Account</a> 
            
           

        </nav>
</header>

    <table border="1" style="border-collapse: collapse; width: calc(100% - 40px); margin: 20px 20px; text-align: center;">

        <tr>
            <th>Order ID</th>
            <th>Buyer Name</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Amount</th>
            <th>Address</th>
            <th>PIN</th>
            <th>Mobile</th>
            <th>Purchase Date</th>
            <th>Status</th>
            <th>Update Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user_first_name'] . " " . $row['user_last_name']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>₹<?php echo $row['total_amount']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['pin']; ?></td>
            <td><?php echo $row['mobile_no']; ?></td>
            <td><?php echo $row['purchase_date']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                    <select name="status">
                        <option value="Pending" <?php if ($row['status'] == 'Pending') echo "selected"; ?>>Pending</option>
                        <option value="Shipped" <?php if ($row['status'] == 'Shipped') echo "selected"; ?>>Shipped</option>
                        <option value="Delivered" <?php if ($row['status'] == 'Delivered') echo "selected"; ?>>Delivered</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
