<?php
// admin/admin_dashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

$admin_id = $_SESSION['admin_id'];
// Get admin data (including last login)
$adminQuery = "SELECT * FROM admins WHERE id='$admin_id'";
$adminResult = $conn->query($adminQuery);
$adminData = $adminResult->fetch_assoc();
$lastLogin = $adminData['last_login'];

// Query orders placed since last login
$orderSummaryQuery = "SELECT orders.*, users.username 
                      FROM orders 
                      JOIN users ON orders.user_id = users.id 
                      WHERE orders.order_date > '$lastLogin'";
$orderSummaryResult = $conn->query($orderSummaryQuery);

$totalOrders = $orderSummaryResult->num_rows;
$totalSales = 0;
$ordersSummary = [];
while($order = $orderSummaryResult->fetch_assoc()){
    $totalSales += $order['total'];
    $ordersSummary[] = $order;
}

// Update admin last_login to the current timestamp for next time
$currentTimestamp = date('Y-m-d H:i:s');
$updateQuery = "UPDATE admins SET last_login='$currentTimestamp' WHERE id='$admin_id'";
$conn->query($updateQuery);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
    
    <!-- Summary Section -->
    <div class="admin-summary">
        <h3>Summary Since Last Login (<?php echo $lastLogin; ?>)</h3>
        <p>Total Orders: <?php echo $totalOrders; ?></p>
        <p>Total Sales: $<?php echo number_format($totalSales, 2); ?></p>
        <?php if($totalOrders > 0){ ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Order ID</th>
                    <th>Buyer</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
                <?php foreach($ordersSummary as $order){ ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No new orders since your last login.</p>
        <?php } ?>
    </div>
    
    <!-- Action Buttons -->
    <div class="admin-actions">
        <button onclick="location.href='admin_add_product.php'">Add New Product</button>
        <button onclick="location.href='admin_delivery_check.php'">Delivery Check</button>
    </div>
    
    <!-- Inventory Table -->
    <h3>Inventory</h3>
    <?php
    $inventoryQuery = "SELECT * FROM products";
    $inventoryResult = $conn->query($inventoryQuery);
    ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php while($product = $inventoryResult->fetch_assoc()){ 
            // Get category name (if available)
            $catName = "Uncategorized";
            if($product['category_id']){
                $catQuery = "SELECT name FROM categories WHERE id=".$product['category_id'];
                $catRes = $conn->query($catQuery);
                if($catRes->num_rows == 1){
                    $catRow = $catRes->fetch_assoc();
                    $catName = $catRow['name'];
                }
            }
        ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo htmlspecialchars($catName); ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td>$<?php echo number_format($product['price'], 2); ?></td>
            <td><?php echo $product['stock']; ?></td>
            <td>
                <a href="admin_edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <a href="../logout.php"><button>Logout</button></a>
    <script src="../script.js"></script>
</body>
</html>
