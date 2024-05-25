<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: jQFormsLogin.php');
    exit();
}

// Database connection
require_once './db_connection.php';

// Get cart ID from POST data
if (isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $user_id = $_SESSION['user_id'];

    // Prepare and execute the SQL statement to delete the item from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $cart_id, $user_id);

    if ($stmt->execute()) {
        // Redirect back to the cart page with a success message
        $_SESSION['cart_item_removed'] = true;
        header('Location: cart.php');
        exit();
    } else {
        // Redirect back to the cart page with an error message
        $_SESSION['cart_item_remove_error'] = true;
        header('Location: cart.php');
        exit();
    }

    $stmt->close();
} else {
    // Redirect back to the cart page if cart_id is not set
    header('Location: cart.php');
    exit();
}

$conn->close();
?>
