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
?>
