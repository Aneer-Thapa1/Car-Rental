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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gather input data
    $car_name = $_POST['car_name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $mileage = $_POST['mileage'];
    $engine = $_POST['engine'];
    $transmission = $_POST['transmission'];
    $fuel_type = $_POST['fuel_type'];
    $description = $_POST['description'];
    $vehicle_type = $_POST['vehicle_type'];
    $quantity = $_POST['quantity'];

    // Handle file upload
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["car_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["car_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["car_image"]["size"] > 5000000) { // 5MB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
            // Insert car details into database
            $stmt = $conn->prepare("INSERT INTO cars (car_name, brand, model, year, price, mileage, engine, transmission, fuel_type, description, image_path, vehicle_type, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssidsisssssi", $car_name, $brand, $model, $year, $price, $mileage, $engine, $transmission, $fuel_type, $description, $target_file, $vehicle_type, $quantity);

            if ($stmt->execute()) {
                header('Location: view_products.php'); // Redirect to view products page
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car</title>
    <link rel="stylesheet" href="../css/add_product.css">
</head>

<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="container">
        <h2>Add New Car</h2>
        <form action="addProduct.php" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="car_name">Car Name:</label>
                    <input type="text" id="car_name" name="car_name" required>
                </div>
                <div class="form-group">
                    <label for="brand">Brand:</label>
                    <input type="text" id="brand" name="brand" required>
                </div>
                <div class="form-group">
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" id="year" name="year" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="mileage">Mileage:</label>
                    <input type="number" id="mileage" name="mileage" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="engine">Engine:</label>
                    <input type="text" id="engine" name="engine" required>
                </div>
                <div class="form-group">
                    <label for="transmission">Transmission:</label>
                    <input type="text" id="transmission" name="transmission" required>
                </div>
                <div class="form-group">
                    <label for="fuel_type">Fuel Type:</label>
                    <input type="text" id="fuel_type" name="fuel_type" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="car_image">Upload Image:</label>
                    <input type="file" id="car_image" name="car_image" required>
                </div>
                <div class="form-group">
                    <label for="vehicle_type">Vehicle Type:</label>
                    <select id="vehicle_type" name="vehicle_type" required>
                        <option value="SUV">SUV</option>
                        <option value="Supercar">Supercar</option>
                        <option value="Sedan">Sedan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
            </div>
            <button type="submit">Add Car</button>
        </form>
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
