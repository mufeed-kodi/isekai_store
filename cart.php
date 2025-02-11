<?php
// cart.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT cart.*, products.name, products.price 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id='$user_id'";
$result = $conn->query($query);
$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Cart</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Your Cart</h2>
    <?php if ($result->num_rows > 0) { ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) {
                $subtotal = $row['price'] * $row['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>$<?php echo number_format($row['price'],2); ?></td>
                    <td>$<?php echo number_format($subtotal,2); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>$<?php echo number_format($total,2); ?></strong></td>
            </tr>
        </table>
        <a href="checkout.php"><button>Proceed to Checkout</button></a>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>
</body>
</html>
