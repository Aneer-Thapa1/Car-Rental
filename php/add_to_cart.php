<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: jQFormsLogin.php');
    exit();
}

// Database connection
require_once './db_connection.php';

$response = ['status' => 'error'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];

    // Check if the car is already in the cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND car_id = ?");
    $stmt->bind_param('ii', $user_id, $car_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Car is already in the cart
        $response['status'] = 'exists';
    } else {
        // Insert the car into the cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, car_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param('ii', $user_id, $car_id);

        if ($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
