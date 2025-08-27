<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'] ?? 1;
    
    // Check if product already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: cart.php");
    exit();
}

// Update quantities
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
    <div class="cart-container">
        <h1>Your Shopping Cart</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <p class="empty-cart">Your cart is empty</p>
        <?php else: ?>
            <form action="cart.php" method="post">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantities[<?php echo $product_id; ?>]" 
                                           value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                </td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <a href="cart.php?remove=<?php echo $product_id; ?>" class="remove-btn">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="total-label">Total:</td>
                            <td colspan="2" class="total-amount">$<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="update-btn">Update Cart</button>
                    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                    <a href="index.php" class="continue-btn">Continue Shopping</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="assets/js/cart.js"></script>
</body>
</html>