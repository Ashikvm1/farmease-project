<?php 
    session_start();

    // Check if the user is logged in, otherwise redirect to login page
    if (!isset($_SESSION['user_email'])) {
        header("Location: index.html");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="account.js"></script>
    <style>
        body, h1, p, button {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0px;
        }

        .header {
    background-color: #232f3e;
    color: white;
    padding: 0; /* Add some horizontal padding */
    display: flex;
    align-items: center;
    justify-content: space-between;
    
    
}


        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header button {
            background-color: #ff5e57;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 100px;
        }

        .header button:hover {
            background-color: #ff2a15;
           
        }

        .container {
            background-color: white;
            
            padding-top: 10px;
            padding-bottom: 10%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .section {
            margin-bottom: 20px;
        }

        .section button {
            padding: 10px 15px;
            background-color: #232f3e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 300px;
            display: block;
            margin: auto;
        }

        .section button:hover {
            background-color: #3a4455;
        }
        #username{
            padding-left: 10px;
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

    <div class="header">
        <h1 id="username">
            <?php 
                if (isset($_SESSION['user_name'])) {
                    echo "Hello, " . $_SESSION['user_name'];
                } else {
                    echo "Hello, Guest";
                }
            ?>
        </h1>
        <button onclick="logout()">Logout</button>
    </div>

    <div class="container">
    

        <div class="section">
            <h2>Your Orders</h2>
            <button onclick="viewOrders()">View Orders</button>
        </div>

        

        <div class="section">
            <h2>Your Lists</h2>
            <button onclick="viewLists()">See All Lists</button>
        </div>
    </div>

    <script>
        function changeProfilePicture() {
            const newPic = prompt("Enter the URL of the new profile picture:");
            if (newPic) {
                document.getElementById("profile-pic").src = newPic;
                alert("Profile picture updated successfully!");
            }
        }

        function editAccountName() {
            const newName = prompt("Enter your new account name:");
            if (newName) {
                document.getElementById("new_name").value = newName;
                document.getElementById("change-name-form").submit();
            }
        }

        function logout() {
            window.location.href = "index.html";
        }

        function viewOrders() {
            
            window.location.href = "orders.php";
        }

        function viewLists() {
        window.location.href = "sold_products.php";
    }
    </script>

</body>
</html>
