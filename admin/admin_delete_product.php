<?php
// admin/admin_delete_product.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $deleteQuery = "DELETE FROM products WHERE id='$product_id'";
    if ($conn->query($deleteQuery) === TRUE) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
