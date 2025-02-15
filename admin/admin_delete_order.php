<?php
// admin/admin_delete_order.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    // Only allow deletion if order status is not "Pending"
    $deleteQuery = "DELETE FROM orders WHERE id='$order_id' AND status != 'Pending'";
    if ($conn->query($deleteQuery) === TRUE) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting order: " . $conn->error;
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
