<?php
// checkout.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$query = "SELECT cart.*, products.price, products.stock 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id='$user_id'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

$total = 0;
while($row = $result->fetch_assoc()){
    $total += $row['price'] * $row['quantity'];
}

// Reset pointer for processing the order
$result->data_seek(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        // Insert the new order only if total is greater than 0
        if($total <= 0){
            throw new Exception("Cart is empty.");
        }
        $orderQuery = "INSERT INTO orders (user_id, total) VALUES ('$user_id', '$total')";
        if (!$conn->query($orderQuery)) {
            throw new Exception($conn->error);
        }
        $order_id = $conn->insert_id;
        
        // Process each cart item
        while($item = $result->fetch_assoc()){
            $price = $item['price'];
            $quantity = $item['quantity'];
            $product_id = $item['product_id'];
            
            // Insert order item
            $orderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            if (!$conn->query($orderItemQuery)) {
                throw new Exception($conn->error);
            }
            
            // Update stock: subtract ordered quantity
            $updateStockQuery = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
            if (!$conn->query($updateStockQuery)) {
                throw new Exception($conn->error);
            }
        }
        // Clear the user's cart
        $conn->query("DELETE FROM cart WHERE user_id='$user_id'");
        $conn->commit();
        echo " <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Order Success</title>
        <link rel='stylesheet' type='text/css' href='styles.css'> <!-- Link to your CSS -->
    </head>
    <body>
        <header>
            <h1>Order Placed Successfully!</h1>
        </header>

        <div class='message-container'>
            <p>Your order has been successfully placed.</p>
            <p>Thank you for shopping with us!</p>
        </div>

        <footer>
            <p>Redirecting you to the homepage...</p>
        </footer>

        <script>
            setTimeout(function(){
                window.location.href = 'index.php'; // Redirect to the homepage
            }, 3000); // 3-second delay before redirect
        </script>
    </body>
    </html>";
        header("refresh:3;url=index.php");
    } catch(Exception $e) {
        $conn->rollback();
        echo "<p>Failed to place order: " . $e->getMessage() . "</p>";
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Checkout</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Checkout</h2>
    <p>Total Amount: $<?php echo number_format($total,2); ?></p>
    <!-- Only display checkout form if cart is not empty -->
    <?php if($total > 0){ ?>
        <form method="POST" action="checkout.php">
            <button type="submit">Place Order</button>
        </form>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>
</body>
</html>
