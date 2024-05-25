<?php
// Database connection
require_once './db_connection.php';

// Fetch cars from the database according to types
function fetchCarsByType($conn, $type) {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE vehicle_type = ?");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    $stmt->close();
    return $cars;
}

$supercars = fetchCarsByType($conn, 'Supercar');
$sedans = fetchCarsByType($conn, 'Sedan');
$suvs = fetchCarsByType($conn, 'SUV');

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Car</title>
    <link rel="stylesheet" href="../css/sellingCars.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="searchBar">
        <input type="text" placeholder="Search for cars...">
        <button class="search">Search</button>
    </div>

    <div class="bar">
        <div class="categoryButtons">
            <button id="supercar" class="categoryButton">Supercar</button>
            <button id="sedan" class="categoryButton">Sedan</button>
            <button id="suv" class="categoryButton">SUV</button>
        </div>
    </div>

    <h1 class="heading">Supercars for sale</h1>
    <div class="cars" id="supercarContainer">
        <?php foreach ($supercars as $car): ?>
            <div class="car">
                <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                <div class="details">
                    <h1><a href="singleCar.php?id=<?php echo $car['id']; ?>"><?php echo htmlspecialchars($car['car_name']); ?></a></h1>
                    <p><?php echo htmlspecialchars($car['year']); ?> Model, <?php echo htmlspecialchars($car['description']); ?></p>
                    <p>$<?php echo htmlspecialchars($car['price']); ?></p>
                    <div class="carBtn">
                        <button class="addCrt" data-car-id="<?php echo $car['id']; ?>">Add to cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h1 class="heading">Sedans for sale</h1>
    <div class="cars" id="sedanContainer">
        <?php foreach ($sedans as $car): ?>
            <div class="car">
                <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                <div class="details">
                    <h1><a href="singleCar.php?id=<?php echo $car['id']; ?>"><?php echo htmlspecialchars($car['car_name']); ?></a></h1>
                    <p><?php echo htmlspecialchars($car['year']); ?> Model, <?php echo htmlspecialchars($car['description']); ?></p>
                    <p>$<?php echo htmlspecialchars($car['price']); ?></p>
                    <div class="carBtn">
                        <button class="addCrt" data-car-id="<?php echo $car['id']; ?>">Add to cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h1 class="heading">SUVs for sale</h1>
    <div class="cars" id="suvContainer">
        <?php foreach ($suvs as $car): ?>
            <div class="car">
                <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                <div class="details">
                    <h1><a href="singleCar.php?id=<?php echo $car['id']; ?>"><?php echo htmlspecialchars($car['car_name']); ?></a></h1>
                    <p><?php echo htmlspecialchars($car['year']); ?> Model, <?php echo htmlspecialchars($car['description']); ?></p>
                    <p>$<?php echo htmlspecialchars($car['price']); ?></p>
                    <div class="carBtn">
                        <button class="addCrt" data-car-id="<?php echo $car['id']; ?>">Add to cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryButtons = document.querySelectorAll('.categoryButton');

            categoryButtons.forEach(button => {
                button.addEventListener('click', function () {
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('active');
                    });
                    button.classList.add('active');
                    const containerId = button.id + 'Container';
                    const container = document.getElementById(containerId);
                    container.scrollIntoView({ behavior: 'smooth' });
                });
            });

            const addCartButtons = document.querySelectorAll('.addCrt');

            addCartButtons.forEach(button => {
                button.addEventListener('click', function () {
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
