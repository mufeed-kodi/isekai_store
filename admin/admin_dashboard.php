<?php
// admin/admin_dashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

// Get list of products with category name (if available)
$query = "SELECT products.*, categories.name AS category_name 
          FROM products 
          LEFT JOIN categories ON products.category_id = categories.id";
$result = $conn->query($query);
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
    <a href="admin_add_product.php"><button>Add New Product</button></a>
    <a href="../logout.php"><button>Logout</button></a>
    <h3>Product List</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($product = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo $product['category_name'] ?? 'Uncategorized'; ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td><?php echo htmlspecialchars($product['description']); ?></td>
            <td>$<?php echo number_format($product['price'],2); ?></td>
            <td><img src="../images/<?php echo htmlspecialchars($product['image']); ?>" width="80" height="80"></td>
            <td>
                <a href="admin_edit_product.php?id=<?php echo $product['id']; ?>">Edit</a> | 
                <a class="delete-btn" href="admin_delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <script src="../dlt.js"></script>
</body>
</html>
