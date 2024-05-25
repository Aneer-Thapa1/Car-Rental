<?php
// Database connection
require_once './db_connection.php';

// Get car ID from query parameter
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($car_id > 0) {
    // Fetch car details from the database
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();

if (!$car) {
    die('Car not found.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['car_name']); ?></title>
    <link rel="stylesheet" href="../css/singleCar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="car-details">
            <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
            <div class="details">
                <h1><?php echo htmlspecialchars($car['car_name']); ?></h1>
                <p>Brand: <?php echo htmlspecialchars($car['brand']); ?></p>
                <p>Model: <?php echo htmlspecialchars($car['model']); ?></p>
                <p>Year: <?php echo htmlspecialchars($car['year']); ?></p>
                <p>Price: $<?php echo htmlspecialchars($car['price']); ?></p>
                <p>Mileage: <?php echo htmlspecialchars($car['mileage']); ?> miles</p>
                <p>Engine: <?php echo htmlspecialchars($car['engine']); ?></p>
                <p>Transmission: <?php echo htmlspecialchars($car['transmission']); ?></p>
                <p>Fuel Type: <?php echo htmlspecialchars($car['fuel_type']); ?></p>
                <p>Description: <?php echo htmlspecialchars($car['description']); ?></p>
                <div class="carBtn">
                    <button class="addCrt" data-car-id="<?php echo $car['id']; ?>">Add to cart</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addCartButton = document.querySelector('.addCrt');

            addCartButton.addEventListener('click', function () {
                const carId = this.getAttribute('data-car-id');

                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `car_id=${carId}`
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again later.',
                        confirmButtonText: 'OK'
                    });
                });
            });
        });

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
