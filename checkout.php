<?php
// checkout.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Calculate total
$query = "SELECT cart.*, products.price 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id='$user_id'";
$result = $conn->query($query);
$total = 0;
while($row = $result->fetch_assoc()){
    $total += $row['price'] * $row['quantity'];
}

// When form is submitted, process the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        // Insert the new order
        $orderQuery = "INSERT INTO orders (user_id, total) VALUES ('$user_id', '$total')";
        if (!$conn->query($orderQuery)) {
            throw new Exception($conn->error);
        }
        $order_id = $conn->insert_id;
        
        // Retrieve cart items again
        $result = $conn->query($query);
        while($item = $result->fetch_assoc()){
            $price = $item['price'];
            $quantity = $item['quantity'];
            $product_id = $item['product_id'];
            $orderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            if (!$conn->query($orderItemQuery)) {
                throw new Exception($conn->error);
            }
        }
        // Clear the user's cart after ordering
        $conn->query("DELETE FROM cart WHERE user_id='$user_id'");
        $conn->commit();
        echo "<p>Order placed successfully!</p>";
    } catch(Exception $e) {
        $conn->rollback();
        echo "<p>Failed to place order: " . $e->getMessage() . "</p>";
    }
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
    <!-- In a real system you would collect payment and shipping details -->
    <form method="POST" action="checkout.php">
        <button type="submit">Place Order</button>
    </form>
</body>
</html>
