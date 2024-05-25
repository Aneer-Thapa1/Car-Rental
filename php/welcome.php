<?php
// Database connection
require_once './db_connection.php';

// Fetch top 3 most expensive cars overall
$query = "
    SELECT * FROM cars
    ORDER BY price DESC
    LIMIT 3;
";

$result = $conn->query($query);
$cars = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <link rel="stylesheet" href="../css/welcome.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <section class="container">
    <div class="slide">
      <?php foreach ($cars as $car): ?>
      <div class="item" style="background-image: url(<?php echo htmlspecialchars($car['image_path']); ?>);">
        <div class="overlay"></div>
        <div class="content">
          <div class="name"><?php echo htmlspecialchars($car['car_name']); ?></div>
          <div class="desc"><?php echo htmlspecialchars($car['description']); ?></div>
          <button class="see-more" data-car-id="<?php echo $car['id']; ?>">See more</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="button">
      <button class="prev"><i class="fa-solid fa-arrow-left-long"></i></button>
      <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </section>

  <div class="searchBar">
    <input type="text" placeholder="Search for cars..">
    <button class="searchBtn">Search</button>
  </div>

  <h1 class="heading">Top Cars</h1>
  
  <section class="topSelling">
    <?php foreach ($cars as $car): ?>
    <div class="car">
      <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
      <div class="details">
        <h1><a href=""><?php echo htmlspecialchars($car['car_name']); ?></a></h1>
        <p><?php echo htmlspecialchars($car['price']); ?>$</p>
        <div class="carBtn">
          <form class="add-to-cart-form" method="post">
            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
            <button type="submit" class="addCrt">Add to cart</button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </section>

  <?php include 'footer.php'; ?>

  <script src="../JS/slider.js"></script>
  <script>
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('add_to_cart.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'exists') {
            Swal.fire({
              icon: 'warning',
              title: 'Already in Cart',
              text: 'This car is already in your cart.',
              confirmButtonText: 'OK'
            });
          } else if (data.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Added to Cart',
              text: 'The car has been added to your cart.',
              confirmButtonText: 'OK'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = 'cart.php';
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'There was an error adding the car to your cart.',
              confirmButtonText: 'OK'
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
      });
    });

    document.querySelectorAll('.see-more').forEach(button => {
      button.addEventListener('click', function () {
        const carId = this.getAttribute('data-car-id');
        window.location.href = `singleCar.php?id=${carId}`;
      });
    });
  </script>
</body>

</html>
