<?php
// add_to_cart.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    // Use the provided quantity or default to 1
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $user_id = $_SESSION['user_id'];

    // Check if product already exists in the cart
    $check = "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    $res = $conn->query($check);
    if ($res->num_rows > 0) {
        // Increase quantity by the amount selected
        $update = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id='$user_id' AND product_id='$product_id'";
        $conn->query($update);
    } else {
        $insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
        $conn->query($insert);
    }
    header("Location: cart.php");
    exit();
}
?>
