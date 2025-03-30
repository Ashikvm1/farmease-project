<?php
session_start();
include 'db_connection.php';

// Ensure only admins can access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get number of users
$user_count_query = "SELECT COUNT(*) AS total_users FROM user";
$user_count_result = $conn->query($user_count_query);
$user_count = $user_count_result->fetch_assoc()['total_users'];

// Get number of products sold (count farmers who have products)
$product_count_query = "SELECT COUNT(id) AS total_sellers FROM products";
$product_count_result = $conn->query($product_count_query);
$product_count = $product_count_result->fetch_assoc()['total_sellers'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #232f3e;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 10px 0;
        }
        .sidebar a:hover {
            background: #3b4a5a;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
        }
        .dashboard {
            display: flex;
            gap: 20px;
        }
        .card {
            background: #f1f1f1;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 200px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="index.html">Logout</a>
    </div>

    <div class="main-content" style="padding-left:40px;;">
        <h1>Admin Dashboard</h1>
        <div class="dashboard">
            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo $user_count; ?></p>
            </div>
            <div class="card">
                <h3>Total products</h3>
                <p><?php echo $product_count; ?></p>
            </div>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
