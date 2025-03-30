<?php
session_start();
include 'db_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all users
$user_query = "SELECT id, user_first_name, user_last_name, user_email FROM user";
$user_result = $conn->query($user_query);

// Handle user creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    $insert_query = "INSERT INTO user (user_first_name, user_last_name, user_email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
    
    if ($conn->query($insert_query) === TRUE) {
        header("Location: manage_users.php?tab=create");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle user deletion (removing user and their products)
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];

    // Delete user's products first
    $delete_products_query = "DELETE FROM products WHERE farmer_id = $user_id";
    $conn->query($delete_products_query);

    // Delete the user
    $delete_user_query = "DELETE FROM user WHERE id = $user_id";
    
    if ($conn->query($delete_user_query) === TRUE) {
        header("Location: manage_users.php?tab=remove");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get active tab from URL
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'create';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            background: <?= $active_tab == 'create' ? '#3b4a5a' : 'transparent' ?>;
        }
        .sidebar a:hover {
            background: #3b4a5a;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #333;
            color: white;
        }
        button {
            padding: 8px 12px;
            background: red;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: darkred;
        }
        input {
            padding: 10px;
            width: calc(100% - 22px);
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-primary {
            background: green;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background: darkgreen;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Manage Users</h2>
        <a href="manage_users.php?tab=create">Create User</a>
        <a href="manage_users.php?tab=remove">Remove User</a>
        <a href="admin.php">Back to Dashboard</a>
    </div>

    <div class="main-content">
        <div class="container">
            <?php if ($active_tab == 'create') { ?>
                <h2>Create New User</h2>
                <form method="POST">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="add_user" class="btn-primary">Add User</button>
                </form>
            <?php } elseif ($active_tab == 'remove') { ?>
                <h2>Remove Users</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $user_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['user_first_name']; ?></td>
                        <td><?php echo $row['user_last_name']; ?></td>
                        <td><?php echo $row['user_email']; ?></td>
                        <td>
                            <a href="manage_users.php?tab=remove&delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure? This will remove the user and their products.')">
                                <button>Delete</button>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
