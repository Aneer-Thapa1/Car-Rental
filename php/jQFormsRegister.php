<?php
require 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $date_of_birth = $_POST['date_of_birth'];
    $street = $_POST['street'];
    $province = $_POST['province'];
    $city = $_POST['city'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password, date_of_birth, street, province, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstName, $lastName, $email, $phone, $password, $date_of_birth, $street, $province, $city);

    // Execute statement
    if ($stmt->execute()) {
        echo "Registration successful.";
        // Redirect to login page or another page
        header("Location: ../pages/login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
