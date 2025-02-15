<?php
// login.php
session_start();
include 'config.php';
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = $conn->real_escape_string($_POST['username']);
    $password_input = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username='$username_input'";
    $result = $conn->query($query);
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify entered password with hashed password from the database
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Incorrect password.";
        }
    } else {
        $errors[] = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Login</h2>
    <?php
    if(!empty($errors)){
        foreach($errors as $error){
            echo "<p class='error'>$error</p>";
        }
    }
    ?>
    <form method="POST" action="login.php">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
