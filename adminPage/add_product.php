<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket";
$message = '';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['addProductBtn'])) {

    $name = $conn->real_escape_string($_POST['name']);
    $price = (float) $_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $image_url = $conn->real_escape_string($_POST['imgUrl']);

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image_url, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $price, $description, $image_url, $category);

    if ($stmt->execute()) {
        $message = "<div class='success-msg'>✅ Product added successfully!</div>";
    } else {
        $message = "<div class='error-msg'>❌ Error: " . $stmt->error . "</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Product</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.ico">
    <link rel="stylesheet" href="add_products.css">
</head>

<body>
    <div class="header">
        <a class="headerText" href="admin.php">ALL - IN - ONE</a>
        <nav class="navigationBar">
            <a class="navigationBarLink" href="admin.php">Admin Dashboard</a>
            <a class="navigationBarLink" href="add_product.php">Add Product</a>
            <a class="logOut-btn" href="logout.php" class="logout">Logout</a>
        </nav>
    </div>
    <div class="content">
        <form class="addProduct" action="add_product.php" method="POST">
            <h2 class="formHeader">Add Product</h2>
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" placeholder="Name...." required>
            </div>
            <div class="form-group">
                <label for="name">Product Price</label>
                <input type="text" id="price" name="price" placeholder="Price...." required>
            </div>
            <div class="form-group">
                <label for="name">Product description</label>
                <input type="text" id="description" name="description" placeholder="Description...." required>
            </div>
            <div class="form-group">
                <label for="name">Product category</label>
                <input type="text" id="category" name="category" placeholder="Category...." required>
            </div>
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="text" id="imgUrl" name="imgUrl" placeholder="ex: https://img.jpg" required>
            </div>
            <div class="form-btns">
                <button type="submit" name="addProductBtn" class="form-btn">Add Product to Store</button>
            </div>
        </form>
        <?php echo $message; ?>
    </div>
</body>

</html>