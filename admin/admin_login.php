<?php
// admin/admin_login.php
session_start();
include '../config.php';
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = $conn->real_escape_string($_POST['username']);
    $password_input = $_POST['password'];
    
    $query = "SELECT * FROM admins WHERE username='$username_input'";
    $result = $conn->query($query);
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password_input, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $errors[] = "Incorrect password.";
        }
    } else {
        $errors[] = "Admin user not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Login</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Admin Login</h2>
    <?php
    if(!empty($errors)){
        foreach($errors as $error){
            echo "<p class='error'>$error</p>";
        }
    }
    ?>
    <form method="POST" action="admin_login.php">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login as Admin</button>
    </form>
</body>
</html>
