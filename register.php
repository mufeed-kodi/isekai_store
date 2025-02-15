<?php
// register.php
include 'config.php';
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape inputs to avoid SQL injection (for production, use prepared statements)
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username_input = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = $_POST['password'];

    // Check if username or email is already registered
    $checkQuery = "SELECT * FROM users WHERE username='$username_input' OR email='$email'";
    $result = $conn->query($checkQuery);
    if ($result->num_rows > 0) {
        $errors[] = "Username or Email already registered.";
    } else {
        // Hash the password using a secure algorithm
        $hashedPassword = password_hash($password_input, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO users (fullname, username, email, password) 
                        VALUES ('$fullname', '$username_input', '$email', '$hashedPassword')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Register</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Register</h2>
    <?php
    if(!empty($errors)){
        foreach($errors as $error){
            echo "<p class='error'>$error</p>";
        }
    }
    ?>
    <form method="POST" action="register.php">
        <label>Full Name:</label><br>
        <input type="text" name="fullname" required><br>
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
