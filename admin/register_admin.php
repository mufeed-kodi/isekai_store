<?php

// config.php
$servername = "localhost";
$username = "root";
$password = "";  // adjust if you have a password set for WAMP
$dbname = "isekai_db2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// register_admin.php
// Set the new admin credentials
$username = 'fido'; // change this to your desired admin username
$passwordPlain = 'alyosha'; // change this to the desired password

// Hash the password
$hashedPassword = password_hash($passwordPlain, PASSWORD_DEFAULT);

// Insert the new admin into the database
$query = "INSERT INTO admins (username, password) VALUES ('$username', '$hashedPassword')";
if ($conn->query($query) === TRUE) {
    echo "New admin registered successfully.";
} else {
    echo "Error: " . $conn->error;
}
?>
