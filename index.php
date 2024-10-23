<?php
session_start();
include 'db.php';

// Fetch products from the database
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>

<body>

    <h1>Products</h1>
    <div>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div style="border: 1px solid #ccc; padding: 10px;">
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"
                    style="width:150px; height:auto;">
                <h2><?php echo $row['name']; ?></h2>
                <p><?php echo $row['description']; ?></p>
                <p>Price: $<?php echo $row['price']; ?></p>
                <form action="cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    <button type="submit">Add to Cart</button>
                </form>

            </div>
        <?php endwhile; ?>
    </div>

    <h2>Your Cart</h2>
    <a href="cart.php">View Cart</a>

</body>

</html>