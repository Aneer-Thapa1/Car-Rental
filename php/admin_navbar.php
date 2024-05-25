<?php
// Ensure the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: jQFormsLogin.php');
    exit();
}

// Use the existing database connection
if (!isset($conn)) {
    require_once './db_connection.php';
}

// Fetch the username from the database
$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare('SELECT username FROM users WHERE id = ?');
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/admin_navbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    

<div class="responsiveNav">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
          <li><a href="addProduct.php">Add Products</a></li>
        <li><a href="view_products.php">View Products</a></li>
        <li><a href="admin_view_orders.php">View Orders</a></li>
        <li><i class="fa-solid fa-sign-out-alt"></i> Logout</li>
    </ul>
</div>

<nav>
    <button class="menu" id="menu">
        <i class="fa-solid fa-bars"></i>
    </button>

   <p class="logo">Susi<span>Motors</span> </p>
    <div class="navItems">
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="addProduct.php">Add Products</a></li>
            <li><a href="view_products.php">View Products</a></li>
            
            <li><a href="admin_view_orders.php">View Orders</a></li>
        </ul>
    </div>
    <div class="profile">
        <a href="admin_profile.php">
           
            <?php echo htmlspecialchars($username); ?>
        </a>
        <a href="logout.php" class="logout">
            <i class="fa-solid fa-sign-out-alt"></i> 
        </a>
    </div>
</nav>
</body>
