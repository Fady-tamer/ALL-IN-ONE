<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit();
}

$printUsername = htmlspecialchars($_SESSION['username']);
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$products = [];

$sql_fetch = "SELECT product_id, name, price, category, image_url, description FROM Products ORDER BY product_id";
$result_fetch = $conn->query($sql_fetch);

if ($result_fetch) {
    while ($row = $result_fetch->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?php echo $printUsername; ?>!</title>
    <link rel="icon" type="image/x-icon" href="../img/icon.ico">
    <link rel="stylesheet" href="user.css">
</head>

<body>
    <div class="header">
        <a class="headerText" href="user.php">ALL - IN - ONE</a>
        <nav class="navigationBar">
            <a class="navigationBarLink" href="">Home</a>
            <a class="navigationBarLink" href="cart.php">
                Cart
                <?php if (empty($cart)) {
                    echo "<span class='cartCounter'>0</span>";
                } else {
                    echo "<span class='cartCounter'>" . count($cart) . "</span>";
                }
                ?>
            </a>
            <a class="navigationBarLink" href="">About Us</a>
            <a class="logOut-btn" href="logout.php" class="logout">Logout</a>
        </nav>
    </div>
    <div class="user-content">
        <h1>Welcome to the ALL - IN - ONE, <?php echo $printUsername; ?>!</h1>
    </div>
    <div class="search-filter-container">
        <div class="search">
            <label for="search">Search</label>
            <input id="search" type="text" onkeyup="search()">
        </div>
        <div class="filter">
            <label for="filter">Filter</label>
            <select name="filter" id="filter" onchange="filter()">
                <option value="all" selected>All</option>
                <?php if (!empty($products)): ?>
                    <?php
                    $uniqueCategories = [];
                    foreach ($products as $product) {
                        $category = $product['category'];
                        if (!in_array($category, $uniqueCategories)) {
                            $uniqueCategories[] = $category;
                        }
                    }
                    ?>
                    <?php
                    foreach ($uniqueCategories as $category):
                        ?>
                        <option value="<?php echo htmlspecialchars($category); ?>">
                            <?php echo htmlspecialchars($category); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="card-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="<?php echo htmlspecialchars($product['image_url']) ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    </div>
                    <div class="product-info">
                        <p class="productCategory"><?php echo htmlspecialchars($product['category']); ?></p>
                        <h3 class="productName"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <form class="product-add-to-cart" method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                        <div class="inputGroup">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" value="1" required>
                        </div>
                        <button name="productSubmit" type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="no-card-container">
        <img class="noCardContentImg" src="../svgs/solid/question.svg" alt="">
        <p class="noCardContentText">No Product have this Name</p>
    </div>
    <div class="footer">
        <p>&copy; 2025 ALL - IN - ONE. All rights reserved.</p>
    </div>

    <script src="user.js"></script>
</body>

</html>