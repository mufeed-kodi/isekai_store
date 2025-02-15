<?php
session_start();
include 'config.php';

// Query all categories
$catQuery = "SELECT * FROM categories ORDER BY name ASC";
$catResult = $conn->query($catQuery);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Categories</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Product Categories</h2>
    <div class="categories">
        <?php while($category = $catResult->fetch_assoc()) { ?>
            <h3><?php echo htmlspecialchars($category['name']); ?></h3>
            <div class="product-list">
                <?php
                $catId = $category['id'];
                $productQuery = "SELECT * FROM products WHERE category_id = $catId";
                $productResult = $conn->query($productQuery);
                if($productResult->num_rows > 0) {
                    while($product = $productResult->fetch_assoc()){
                ?>
                    <div class="product-item">
                        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             width="200" height="200">
                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <p>Stock: <?php echo $product['stock']; ?></p>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <!-- Quantity selector -->
                            <label for="quantity_<?php echo $product['id']; ?>">Qty:</label>
                            <input type="number" name="quantity" id="quantity_<?php echo $product['id']; ?>" value="1" min="1">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                <?php
                    }
                } else {
                    echo "<p>No products in this category.</p>";
                }
                ?>
            </div>
        <?php } ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
