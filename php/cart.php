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

// Database connection
require_once './db_connection.php';

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT cart.id as cart_id, cars.id as car_id, cars.car_name, cars.price, cars.image_path, cart.quantity FROM cart JOIN cars ON cart.car_id = cars.id WHERE cart.user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($cart_id, $car_id, $car_name, $price, $image_path, $quantity);

$cart_items = [];
while ($stmt->fetch()) {
    $cart_items[] = [
        'cart_id' => $cart_id,
        'car_id' => $car_id,
        'car_name' => $car_name,
        'price' => $price,
        'image_path' => $image_path,
        'quantity' => $quantity
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
    <title>Your Cart</title>
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2>Your Cart</h2>
        <?php if (isset($_SESSION['cart_item_removed'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Removed',
                    text: 'The item has been removed from your cart.',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['cart_item_removed']); ?>
            </script>
        <?php endif; ?>
        <?php if (isset($_SESSION['cart_item_remove_error'])): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error removing the item from your cart.',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['cart_item_remove_error']); ?>
            </script>
        <?php endif; ?>
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <div class="cart-table">
                <div class="cart-header">
                    <div class="header-item">Product Details</div>
                    <div class="header-item">Quantity</div>
                    <div class="header-item">Price</div>
                    <div class="header-item">Total</div>
                    <div class="header-item">Actions</div>
                </div>
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <div class="product-details">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['car_name']); ?>" class="cart-item-image">
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($item['car_name']); ?></h3>
                        </div>
                    </div>
                    <div class="quantity-control">
                        <button type="button" class="quantity-button decrease">-</button>
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" readonly>
                        <button type="button" class="quantity-button increase">+</button>
                    </div>
                    <div class="price"><?php echo htmlspecialchars($item['price']); ?>$</div>
                    <div class="total-price"><?php echo $item['price'] * $item['quantity']; ?>$</div>
                    <div class="actions">
                        <form action="remove_from_cart.php" method="post" class="inline-form">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <button type="submit" class="action-button remove-button">Remove</button>
                        </form>
                        <form action="place_order.php" method="post" class="inline-form place-order-form">
                            <input type="hidden" name="car_id" value="<?php echo $item['car_id']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>" class="order-quantity">
                            <button type="submit" class="action-button order-button">Order Now</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach(item => {
                const decreaseButton = item.querySelector('.decrease');
                const increaseButton = item.querySelector('.increase');
                const quantityInput = item.querySelector('input[name="quantity"]');
                const itemPrice = parseFloat(item.querySelector('.price').textContent.replace('$', ''));
                const totalPriceElement = item.querySelector('.total-price');
                const orderQuantityInput = item.querySelector('.order-quantity');

                decreaseButton.addEventListener('click', () => {
                    let quantity = parseInt(quantityInput.value);
                    if (quantity > 1) {
                        quantity--;
                        quantityInput.value = quantity;
                        orderQuantityInput.value = quantity;
                        totalPriceElement.textContent = (itemPrice * quantity).toFixed(2) + '$';
                    }
                });

                increaseButton.addEventListener('click', () => {
                    let quantity = parseInt(quantityInput.value);
                    quantity++;
                    quantityInput.value = quantity;
                    orderQuantityInput.value = quantity;
                    totalPriceElement.textContent = (itemPrice * quantity).toFixed(2) + '$';
                });

                const orderForm = item.querySelector('.place-order-form');
                orderForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(orderForm);

                    fetch('place_order.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Placed',
                                text: data.message,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'viewOrders.php';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
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
    </script>
</body>
</html>
