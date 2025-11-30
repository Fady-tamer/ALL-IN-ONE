<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.ico">
    <link rel="stylesheet" href="Cart.css">
</head>

<body>
    <div class="header">
        <a class="headerText" href="user.php">ALL - IN - ONE</a>
        <nav class="navigationBar">
            <a class="navigationBarLink" href="user.php">Home</a>
            <a class="navigationBarLink" href="">
                Cart
                <span class="cartCounter">0</span>
            </a>
            <a class="navigationBarLink" href="">About Us</a>
            <a class="logOut-btn" href="logout.php" class="logout">Logout</a>
        </nav>
    </div>
    <div class="main-container">
        
    </div>
    <div class="footer">
        <p>&copy; 2025 ALL - IN - ONE. All rights reserved.</p>
    </div>

    <script src="user.js"></script>
</body>

</html>