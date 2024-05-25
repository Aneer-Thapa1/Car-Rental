<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

// Database connection
require_once './db_connection.php';

// Get POST data
$user_id = $_SESSION['user_id'];
$car_id = intval($_POST['car_id']);
$quantity = intval($_POST['quantity']);

// Fetch the current quantity of the car from the database
$stmt = $conn->prepare("SELECT quantity, price FROM cars WHERE id = ?");
$stmt->bind_param('i', $car_id);
$stmt->execute();
$stmt->bind_result($available_quantity, $price);
$stmt->fetch();
$stmt->close();

if ($quantity > $available_quantity) {
    echo json_encode(['status' => 'error', 'message' => 'Requested quantity exceeds available stock.']);
    exit();
}

// Calculate total price
$total_price = $quantity * $price;

// Place the order
$stmt = $conn->prepare("INSERT INTO orders (user_id, car_id, quantity, total_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param('iiid', $user_id, $car_id, $quantity, $total_price);

if ($stmt->execute()) {
    // Update the quantity in the cars table
    $stmt_update = $conn->prepare("UPDATE cars SET quantity = quantity - ? WHERE id = ?");
    $stmt_update->bind_param('ii', $quantity, $car_id);
    $stmt_update->execute();
    $stmt_update->close();

    // Remove the item from the cart
    $stmt_delete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND car_id = ?");
    $stmt_delete->bind_param('ii', $user_id, $car_id);
    $stmt_delete->execute();
    $stmt_delete->close();

    echo json_encode(['status' => 'success', 'message' => 'Order placed successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to place order.']);
}

$stmt->close();
$conn->close();
?>
