<?php
require_once './db_connection.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];

    // Check if passwords match
    if ($password !== $retype_password) {
        $error_message = 'Passwords do not match. Please retype the passwords.';
    } else {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Check if the email already exists
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            $error_message = 'Email already exists. Please use a different email.';
        } else {
            // Email does not exist, proceed with registration
            $stmt->close();

            $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $username, $email, $password_hashed);

            if ($stmt->execute()) {
                // Redirect to the login page upon successful registration
                header('Location: jQFormsLogin.php');
                exit();
            } else {
                $error_message = 'Registration failed: ' . $stmt->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>

    <form id="register" class="register" action="" method="POST">


        <div class="name">
            <input type="text" id="username" name="username" placeholder="Username" maxlength="45"
                title="Enter your username here." required>
        </div>

        <div class="contact">
            <input type="email" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                required>
        </div>

        <div class="password">
            <input type="password" name="password" placeholder="Password" minlength="8" required>
            <input type="password" name="retype_password" placeholder="Re-type password" minlength="8" required>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <button id="submit" class="registerBtn" type="submit">Register</button>
        <p>Already have an account? <a href="jQFormsLogin.php">Login</a></p>

    </form>

</body>

</html>
