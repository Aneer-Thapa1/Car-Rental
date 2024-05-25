<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: jQFormsLogin.php');
    exit();
}

// Database connection
require_once 'db_connection.php';

// Update order status if a request is made
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Update the order status to completed
    $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        $_SESSION['order_updated'] = true;
    } else {
        $_SESSION['order_update_error'] = true;
    }

    $stmt->close();
}

// Fetch pending orders
$pendingOrders = [];
$stmt = $conn->prepare("SELECT o.id, c.car_name, c.brand, c.model, c.year, o.quantity, o.total_price, o.order_date, o.status 
                        FROM orders o
                        JOIN cars c ON o.car_id = c.id
                        WHERE o.status = 'pending'");
$stmt->execute();
$stmt->bind_result($order_id, $car_name, $brand, $model, $year, $quantity, $total_price, $order_date, $status);
while ($stmt->fetch()) {
    $pendingOrders[] = [
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

// Fetch completed orders
$completedOrders = [];
$stmt = $conn->prepare("SELECT o.id, c.car_name, c.brand, c.model, c.year, o.quantity, o.total_price, o.order_date, o.status 
                        FROM orders o
                        JOIN cars c ON o.car_id = c.id
                        WHERE o.status = 'completed'");
$stmt->execute();
$stmt->bind_result($order_id, $car_name, $brand, $model, $year, $quantity, $total_price, $order_date, $status);
while ($stmt->fetch()) {
    $completedOrders[] = [
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
    <?php include 'admin_navbar.php'; ?>

    <div class="container">
        <h2>Pending Orders</h2>
        <?php if (isset($_SESSION['order_updated'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Order Updated',
                    text: 'The order has been marked as completed.',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['order_updated']); ?>
            </script>
        <?php endif; ?>
        <?php if (isset($_SESSION['order_update_error'])): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error updating the order status.',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['order_update_error']); ?>
            </script>
        <?php endif; ?>
        <?php if (empty($pendingOrders)): ?>
            <div class="no-orders">
                <img src="../images/no_orders.svg" alt="No Orders Available">
                <p>No pending orders available.</p>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingOrders as $order): ?>
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
                        <td>
                            <form action="admin_view_orders.php" method="post">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" class="complete-order">Mark as Completed</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h2>Completed Orders</h2>
        <?php if (empty($completedOrders)): ?>
            <div class="no-orders">
                <img src="../images/no_orders.svg" alt="No Orders Available">
                <p>No completed orders available.</p>
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
                    <?php foreach ($completedOrders as $order): ?>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var menuBtn = document.getElementById("menu");
            var nav = document.querySelector(".responsiveNav");

            function toggleNav() {
                if (nav.style.display === "flex") {
                    nav.style.display = "none";
                } else {
                    nav.style.display = "flex";
                }
            }

            menuBtn.addEventListener("click", function (event) {
                event.stopPropagation();
                toggleNav();
            });

            document.addEventListener("click", function (event) {
                if (event.target !== nav && event.target !== menuBtn && !nav.contains(event.target)) {
                    nav.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>
