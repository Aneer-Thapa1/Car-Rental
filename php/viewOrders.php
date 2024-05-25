<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require_once './db_connection.php';

// Fetch orders from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT o.id, c.car_name, c.brand, c.model, c.year, o.quantity, o.total_price, o.order_date, o.status 
                        FROM orders o
                        JOIN cars c ON o.car_id = c.id
                        WHERE o.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($order_id, $car_name, $brand, $model, $year, $quantity, $total_price, $order_date, $status);

$orders = [];
while ($stmt->fetch()) {
    $orders[] = [
        'order_id' => $order_id,
        'car_name' => $car_name,
        'brand' => $brand,
        'model' => $model,
        'year' => $year,
        'quantity' => $quantity,
        'total_price' => $total_price,
        'order_date' => $order_date,
        'status' => $status
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="../css/view_orders.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2>Your Orders</h2>
        <?php if (isset($_SESSION['order_success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Order Placed',
                    text: 'Your order has been placed successfully!',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['order_success']); ?>
            </script>
        <?php endif; ?>
        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <img src="../images/no_orders.svg" alt="No Orders Available">
                <p>No orders available.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Car Name</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['car_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['brand']); ?></td>
                        <td><?php echo htmlspecialchars($order['model']); ?></td>
                        <td><?php echo htmlspecialchars($order['year']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>
