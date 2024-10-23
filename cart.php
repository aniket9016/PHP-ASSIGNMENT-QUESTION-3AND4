<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle actions (add, update, remove) here...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    // Fetch product details from the database
    $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
    $product = $result->fetch_assoc();

    if ($action == 'add') {
        // Add to cart
        if (isset($_SESSION['cart'][$product_id])) {
            // If product already exists in cart, just increase the quantity
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            // Add new item to the cart
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }

    if ($action == 'update') {
        // Update cart item quantity
        $quantity = $_POST['quantity'];
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            // If quantity is 0, remove the item from the cart
            unset($_SESSION['cart'][$product_id]);
        }
    }

    if ($action == 'remove') {
        // Remove item from cart
        unset($_SESSION['cart'][$product_id]);
    }

    // Redirect to cart page to reflect the changes
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
</head>

<body>

    <h1>Your Cart</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if (!empty($_SESSION['cart'])):
                foreach ($_SESSION['cart'] as $id => $item):
                    // Fetch product details to get image
                    $result = $conn->query("SELECT image FROM products WHERE id = $id");
                    $product = $result->fetch_assoc();

                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><img src="images/<?php echo $product['image']; ?>" alt="<?php echo $item['name']; ?>"
                                style="width:100px;"></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0">
                                <button type="submit">Update</button>
                            </form>
                        </td>
                        <td><?php echo $subtotal; ?></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4">Total</td>
                    <td><?php echo $total; ?></td>
                    <td></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="6">Your cart is empty!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php">Continue Shopping</a>

    <h1>Checkout</h1>
    <form action="checkout.php" method="post">
        <label for="customer_name">Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br>

        <label for="customer_email">Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br>

        <button type="submit">Place Order</button>
    </form>

</body>