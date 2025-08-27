<?php
include 'db.php'; // make sure this sets up $pdo
session_start();

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $inputPassword = $_POST['password'];

    // Use prepared statement for security
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($inputPassword, $user['password'])) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['user_email'] = $email;
            header('Location: admin.php');
            exit();
        } else {
            $errorMessage = 'Invalid password.';
        }
    } else {
        $errorMessage = 'Invalid email address.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link href="css/login.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <h2>Login to Admin Panel</h2>

        <?php if (!empty($errorMessage)): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p><a href="forgot-password.php">Forgot Password?</a></p>
        </form>
    </div>
</body>
</html>
