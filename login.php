<?php
include 'db.php';
// Start session to store login status
session_start();

// Connect to the database


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $inputPassword = $_POST['password'];

    // Check if the user exists
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password using password_verify()
        if (password_verify($inputPassword, $user['password'])) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['user_email'] = $email;
            header('Location: admin.php'); // Redirect to admin page
            exit();
        } else {
            $errorMessage = 'Invalid password.';
        }
    } else {
        $errorMessage = 'Invalid email address.';
    }
}


$conn->close();
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
