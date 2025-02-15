<?php
session_start();
include 'config.php';

// Fetch all categories
$catQuery = "SELECT * FROM categories";
$catResult = $conn->query($catQuery);

// Also fetch products with no category assigned
$uncatQuery = "SELECT * FROM products WHERE category_id IS NULL";
$uncatResult = $conn->query($uncatQuery);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Explore Products</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Explore Products</h2>
    
    <div class="categories">
        <?php while($category = $catResult->fetch_assoc()) { ?>
            <h3><?php echo htmlspecialchars($category['name']); ?></h3>
            <div class="product-list">
                <?php
                $catId = $category['id'];
                $productQuery = "SELECT * FROM products WHERE category_id = $catId";
                $productResult = $conn->query($productQuery);
                while($product = $productResult->fetch_assoc()){
                ?>
                    <div class="product-item">
                        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="200" height="200">
                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <p>Stock: <?php echo $product['stock']; ?></p>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php if($uncatResult->num_rows > 0){ ?>
        <h3>Uncategorized</h3>
        <div class="product-list">
            <?php while($product = $uncatResult->fetch_assoc()){ ?>
                <div class="product-item">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="200" height="200">
                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                    <p>Stock: <?php echo $product['stock']; ?></p>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <script src="script.js"></script>
</body>
</html>
