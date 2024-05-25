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
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="responsiveNav">
    <ul>
      <li><a href="welcome.php">Home</a></li>
      <li><a href="sellingCars.php">Buy Car</a></li>
      <li><a href="orders.php">View orders</a></li>
      <li><a href="cart.php">Cart</a></li>
    </ul>
  </div>

  <nav>
    <button class="menu" id="menu">
      <i class="fa-solid fa-bars"></i>
    </button>

    <p class="logo">Susi<span>Motors</span> </p>
    <div class="navItems">
      <ul>
        <li><a href="welcome.php">Home</a></li>
        <li><a href="sellingCars.php">Buy Car</a></li>
        <li><a href="viewOrders.php">View orders</a></li>
      </ul>
    </div>
    <div class="profile">
      <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
      
      <?php if (isset($_SESSION['user_id'])): ?>
          <i class="fa-solid fa-user"></i>
        <a href="logout.php"> <i class="fa-solid fa-sign-out-alt"></i> </a>
      <?php else: ?>
        <a href="login.php">
          <i class="fa-solid fa-user"></i>
          Login
        </a>
      <?php endif; ?>
    </div>
  </nav>

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
