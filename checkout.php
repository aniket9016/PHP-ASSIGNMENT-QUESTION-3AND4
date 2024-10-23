<?php
session_start();
include 'db.php';

if (empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
    exit;
}

// Assuming you are capturing customer details during checkout
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$order_date = date('Y-m-d H:i:s');
$total = 0;

// Calculate total
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert order into `orders` table
$stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, order_date, total) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $customer_name, $customer_email, $order_date, $total);
$stmt->execute();
$order_id = $stmt->insert_id; // Get the ID of the inserted order

// Insert each cart item into `order_items` table
foreach ($_SESSION['cart'] as $id => $item) {
    $product_name = $item['name'];
    $product_price = $item['price'];
    $quantity = $item['quantity'];
    $subtotal = $product_price * $quantity;

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdid", $order_id, $id, $product_name, $product_price, $quantity, $subtotal);
    $stmt->execute();
}

// Clear the cart after checkout
unset($_SESSION['cart']);

echo "Order placed successfully!";
?>

<a href="index.php">Go back to Shopping</a>