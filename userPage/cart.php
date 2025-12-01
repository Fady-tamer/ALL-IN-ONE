<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit();
}
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['remove'])) {
    $index_to_remove = filter_input(INPUT_POST, 'remove_index', FILTER_VALIDATE_INT);
    if ($index_to_remove !== false && $index_to_remove >= 0 && isset($cart[$index_to_remove])) {
        unset($cart[$index_to_remove]);
        $cart = array_values($cart);
        $_SESSION['cart'] = $cart;
        header("Location: cart.php");
        exit();
    }
}
if (isset($_POST['productSubmit'])) {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if ($product_id !== false && $quantity > 0) {
        $stmt = $conn->prepare("SELECT name, price FROM Products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result_fetch = $stmt->get_result();

        if ($row = $result_fetch->fetch_assoc()) {
            $cart_item = [
                'product_id' => $product_id,
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $quantity
            ];
            $found = false;
            foreach ($cart as $key => $item) {
                if ($item['product_id'] == $product_id) {
                    $cart[$key]['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = $cart_item;
            }
            $_SESSION['cart'] = $cart;
        }
        $stmt->close();
        header("Location: cart.php");
        exit();
    }
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
    <link rel="stylesheet" href="cart.css">
</head>

<body>
    <div class="header">
        <a class="headerText" href="user.php">ALL - IN - ONE</a>
        <nav class="navigationBar">
            <a class="navigationBarLink" href="user.php">Home</a>
            <a class="navigationBarLink" href="">
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
    <div class="main-container">
        <h2 class="mainContentHeader">Your Shopping Cart</h2>
        <?php if (empty($cart)): ?>
            <p class="emptyCart">Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($cart as $index => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $grand_total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo (int) $item['quantity']; ?></td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="remove_index" value="<?php echo $index; ?>">
                                    <button class="remove" name="remove" type="submit">X</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>$<?php echo number_format($grand_total, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>
    <div class="footer">
        <p>&copy; 2025 ALL - IN - ONE. All rights reserved.</p>
    </div>

    <script src="user.js"></script>
</body>

</html>