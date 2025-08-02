<?php
include 'db.php';
session_start();
if (!isset($_SESSION['otp_verified'])) {
    header("Location: forgot-password.php");
    exit;
}




if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['email'];

    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: login.php"); // Redirect to login after password reset
        exit;
    } else {
        $message = "Failed to reset password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
     <link rel="icon" type="image/png" href="img/logo.png">
    <link href="css/reset.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <h2>Reset Password</h2>

        <?php if (!empty($message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="password" name="password" placeholder="Enter new password" required>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    </div>
</body>


</html>
