<?php
session_start();

// Redirect if OTP was not sent
if (!isset($_SESSION['otp_sent']) || !isset($_SESSION['email'])) {
    header("Location: forgot-password.php");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    if (!isset($_SESSION['otp']) || $entered_otp != $_SESSION['otp']) {
        $message = "Invalid OTP.";
    } else {
        $_SESSION['otp_verified'] = true;
        header("Location: reset-password.php"); // Redirect to reset password
        exit;
    }
}

// Include PostgreSQL connection in case needed
require 'db.php'; // $pdo available for PostgreSQL queries
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link href="css/verify.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <h2>Verify OTP</h2>

        <?php if (!empty($message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="number" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
    </div>
</body>
</html>
