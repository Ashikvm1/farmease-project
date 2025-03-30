<?php 
session_start();
include "db_connection.php"; // Ensure this file contains your database connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Handle product deletion
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

// Handle product update
if (isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $new_price = $_POST['price'];
    $new_stock = $_POST['stock'];

    $update_sql = "UPDATE products SET price = ?, stock = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("dii", $new_price, $new_stock, $product_id);
    $stmt->execute();
}

// Fetch sold products
$sql = "SELECT * FROM products WHERE farmer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Sold Products</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 20px;
        }
        .product {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .product img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .form-container {
            text-align: center;
            margin-top: 10px;
        }
        .form-container input {
            padding: 5px;
            margin: 5px;
            width: 80px;
        }
        .form-container button {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .delete-btn {
            background-color: #dc3545;
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <div class="navbar">
        <h1 class="logo">FarmEase</h1>
    </div>
    <nav style="background-color: #27dd2797; padding: 10px; text-align: center">
        <a href="home.html">Home</a> |
        <a href="sell.html">Sell Products</a> |
        <a href="account.php">Account</a>
    </nav>
</header>

<div class="container">
    <h2>Your Sold Products</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row['price']); ?> per kg</p>
                <p><strong>Stock:</strong> <?php echo htmlspecialchars($row['stock']); ?></p>

                <!-- Edit Form -->
                <form class="form-container" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="number" name="price" value="<?php echo $row['price']; ?>" step="0.01">
                    <input type="number" name="stock" value="<?php echo $row['stock']; ?>">
                    <button type="submit" name="edit_product">Update</button>
                </form>

                <!-- Delete Button -->
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete_product" class="delete-btn">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No sold products found.</p>
    <?php endif; ?>

</div>

</body>
</html>
