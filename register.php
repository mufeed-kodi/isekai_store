<?php
// register.php
include 'config.php';
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape inputs to avoid SQL injection (for production, use prepared statements)
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username_input = $conn->real_escape_string($_POST['username']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $address = $conn->real_escape_string($_POST['address']);
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
        $insertQuery = "INSERT INTO users (fullname, username, phone_number, address, email, password) 
                        VALUES ('$fullname', '$username_input', '$phone_number', '$address', '$email', '$hashedPassword')";
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
   <form method="POST" action="register.php" onsubmit="return validatePassword()">
    <label>Full Name</label>
    <input type="text" name="fullname" required>
    <label>Username</label>
    <input type="text" name="username" required>
    <label>Phone number</label>
    <input type="text" name="phone_number" required>
    <label>Address</label>
    <input type="text" name="address" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" id="password" required>
    <!-- Confirm Password field -->
    <label>Confirm Password</label>
    <input type="password" name="confirm_password" id="confirm_password" required>
    <!-- Toggle Password Visibility -->
    <input type="checkbox" onclick="togglePasswordVisibility()">
    
    <p id="password-error" style="color: red; display: none;">Passwords do not match. Please try again.</p>
    
    <button type="submit">Register</button>
</form>

<script>
    // Function to toggle password visibility
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("password");
        var confirmPasswordField = document.getElementById("confirm_password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            confirmPasswordField.type = "text";
        } else {
            passwordField.type = "password";
            confirmPasswordField.type = "password";
        }
    }

    // Function to validate password confirmation
    function validatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        
        // Check if the passwords match
        if (password !== confirmPassword) {
            // Show error message
            document.getElementById("password-error").style.display = "block";
            return false;  // Prevent form submission
        } else {
            // Hide error message if passwords match
            document.getElementById("password-error").style.display = "none";
            return true;  // Allow form submission
        }
    }
</script>



</body>
</html>
