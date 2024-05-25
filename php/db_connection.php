<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Your database username
$password = "anir2080"; // Your database password
$dbname = "susi_motors"; // Your database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
