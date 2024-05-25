<?php
require_once './db_connection.php';

// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT id, password, admin FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $admin);
    $stmt->fetch();

    if ($hashed_password && password_verify($password, $hashed_password)) {
        if ($admin) {
            $_SESSION['admin_id'] = $id;
            setcookie('admin_id', $id, time() + 86400, "/"); // Set a cookie that expires in 1 day
            header('Location: admin_dashboard.php'); // Redirect to admin dashboard
        } else {
            $_SESSION['user_id'] = $id;
            setcookie('user_id', $id, time() + 86400, "/"); // Set a cookie that expires in 1 day
            header('Location: welcome.php'); // Redirect to welcome page
        }
        exit();
    } else {
        $error_message = 'Invalid email or password!';
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <form id="login" class="login" action="" method="post">
        <h3>Login</h3>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <input type="email" name="email" id="email" placeholder="Email" required aria-label="Email" aria-required="true">
        <input type="password" name="password" id="password" minlength="8" placeholder="Password" required aria-label="Password" aria-required="true">
        <button type="submit">Login</button>
        <p>Not a member yet? <a href="jQFormsRegister.php">Register</a> </p>
    </form>

</body>

</html>
