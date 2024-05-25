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
require_once './db_connection.php';

// Fetch the number of customers
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE admin = 0");
$stmt->execute();
$stmt->bind_result($customer_count);
$stmt->fetch();
$stmt->close();

// Fetch the number of cars
$stmt = $conn->prepare("SELECT COUNT(*) FROM cars");
$stmt->execute();
$stmt->bind_result($car_count);
$stmt->fetch();
$stmt->close();

// Fetch the number of orders
$stmt = $conn->prepare("SELECT COUNT(*) FROM orders");
$stmt->execute();
$stmt->bind_result($order_count);
$stmt->fetch();
$stmt->close();

// Fetch customer details excluding admin
$stmt = $conn->prepare("SELECT id, username, email FROM users WHERE admin = 0");
$stmt->execute();
$stmt->bind_result($user_id, $username, $email);

$customers = [];
while ($stmt->fetch()) {
    $customers[] = [
        'id' => $user_id,
        'username' => $username,
        'email' => $email
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
   <?php include 'admin_navbar.php'; ?>

    <div class="container">
        <div class="upper-container">
            <div class="stat-box">
                <h3>Number of Customers</h3>
                <p><?php echo $customer_count; ?></p>
            </div>
            <div class="stat-box">
                <h3>Cars Available</h3>
                <p><?php echo $car_count; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Orders</h3>
                <p><?php echo $order_count; ?></p>
            </div>
        </div>

        <div class="lower-container">
            <h2>All Customers</h2>
            <div class="table-container">
                <?php if (empty($customers)): ?>
                    <p>No customers found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['id']); ?></td>
                                <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

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

<?php
$conn->close();
?>
