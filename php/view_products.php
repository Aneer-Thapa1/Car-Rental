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

// Fetch products from the database
$stmt = $conn->prepare("SELECT id, car_name, brand, model, year, price, mileage, engine, transmission, fuel_type, description, image_path, vehicle_type, quantity FROM cars");
$stmt->execute();
$stmt->bind_result($id, $car_name, $brand, $model, $year, $price, $mileage, $engine, $transmission, $fuel_type, $description, $image_path, $vehicle_type, $quantity);

$products = [];
while ($stmt->fetch()) {
    $products[] = [
        'id' => $id,
        'car_name' => $car_name,
        'brand' => $brand,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'mileage' => $mileage,
        'engine' => $engine,
        'transmission' => $transmission,
        'fuel_type' => $fuel_type,
        'description' => $description,
        'image_path' => $image_path,
        'vehicle_type' => $vehicle_type,
        'quantity' => $quantity
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="../css/view_products.css">
</head>

<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="container">
        <h2>View Cars</h2>
        <?php if (empty($products)): ?>
            <div class="no-products">
                <img src="../images/no_products.svg" alt="No Products Available">
                <p>No cars available.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car Name</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Price</th>
                        <th>Mileage</th>
                        <th>Engine</th>
                        <th>Transmission</th>
                        <th>Fuel Type</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Vehicle Type</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['car_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['brand']); ?></td>
                        <td><?php echo htmlspecialchars($product['model']); ?></td>
                        <td><?php echo htmlspecialchars($product['year']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['mileage']); ?></td>
                        <td><?php echo htmlspecialchars($product['engine']); ?></td>
                        <td><?php echo htmlspecialchars($product['transmission']); ?></td>
                        <td><?php echo htmlspecialchars($product['fuel_type']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Car Image" width="100"></td>
                        <td><?php echo htmlspecialchars($product['vehicle_type']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>
