<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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

$message = '';
$products = [];

$sql_fetch = "SELECT product_id, name, price, category, image_url FROM Products ORDER BY product_id";
$result_fetch = $conn->query($sql_fetch);

if ($result_fetch) {
    while ($row = $result_fetch->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    $message .= "<div class='error-msg'>Error fetching products: " . $conn->error . "</div>";
}

if (isset($_POST['delete_product'])) {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

    $stmt = $conn->prepare("DELETE FROM Products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $message .= "<div class='success-msg'>Product deleted successfully.</div>";
        header("Location: admin.php");
    } else {
        $message .= "<div class='error-msg'>Error deleting product: " . $conn->error . "</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.ico">
    <link rel="stylesheet" href="Admin.css">
</head>

<body>
    <div class="header">
        <a class="headerText" href="admin.php">ALL - IN - ONE</a>
        <nav class="navigationBar">
            <a class="navigationBarLink" href="">Admin Dashboard</a>
            <a class="navigationBarLink" href="add_product.php">Add Product</a>
            <a class="logOut-btn" href="logout.php" class="logout">Logout</a>
        </nav>
    </div>
    <?php echo $message; ?>
    <div class="products">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://placehold.co/400x200/4CAF50/ffffff?text=Image+Missing'); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    </div>
                    <div class="product-info">
                        <p>Category:
                            <?php echo htmlspecialchars($product['category']); ?>
                        </p>
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <form action="admin.php" method="POST" class="deleteProduct">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" name="edit_product" class="edit-btn">Edit Product</button>
                        <button type="submit" name="delete_product" class="delete-btn">Delete Product</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="footer">
        <p>&copy; 2025 ALL - IN - ONE. All rights reserved.</p>
    </div>
</body>

</html>