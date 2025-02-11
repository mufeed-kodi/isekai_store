<?php
// index.php
session_start();
include 'config.php';

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Explore</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Explore Products</h2>
    <div class="product-list">
        <?php while ($product = $result->fetch_assoc()) { ?>
            <div class="product-item">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="200" height="200">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>$<?php echo number_format($product['price'],2); ?></p>
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php } ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
