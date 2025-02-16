<?php
// update_cart.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 'quantities' is an associative array: cart id => new quantity
    if(isset($_POST['quantities']) && is_array($_POST['quantities'])){
        foreach($_POST['quantities'] as $cart_id => $new_quantity) {
            $new_quantity = intval($new_quantity);
            if($new_quantity > 0){
                $updateQuery = "UPDATE cart SET quantity = $new_quantity WHERE id = $cart_id AND user_id = '$user_id'";
                $conn->query($updateQuery);
            }
        }
    }
}
header("Location: cart.php");
exit();
?>
