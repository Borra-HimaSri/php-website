<?php 
include 'db.php'; // PostgreSQL PDO connection
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// $conn->connect_error check removed since we're using PDO

$message = '';

// Handle OTP generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_otp'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email format.';
    } else {
        try {
            // Replace MySQLi prepare/execute with PDO
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                $_SESSION['otp'] = rand(100000, 999999);
                $_SESSION['email'] = $email;
                $_SESSION['otp_sent'] = true;
                session_regenerate_id(true);
            } else {
                $message = 'Email address not found.';
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password</title>
  <link rel="icon" type="image/png" href="img/logo.png">
  <link href="css/forget.css" rel="stylesheet">
  
  <!-- EmailJS -->
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
  <script>
    (function() {
        emailjs.init("0IDgFmufkELwUUb06");
    })();

    function sendOTP() {
        var email = "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>";
        var otp = "<?php echo isset($_SESSION['otp']) ? $_SESSION['otp'] : ''; ?>";

        if (!email || !otp) {
            alert("Email or OTP is missing. Cannot send email.");
            return;
        }

        console.log("EmailJS payload:", {from_name: "MySelf", reply_to: email, otp: otp});

        emailjs.send("service_ovwfmpx", "template_ht2sxdh", {
            from_name: "MySelf",
            reply_to: email,
            otp: otp
        }).then(function(response) {
            alert("OTP sent successfully!");
            window.location.href = "verify-otp.php";
        }, function(error) {
            alert("Failed to send OTP. Check console.");
            console.error("EmailJS Error:", error);
        });
    }
  </script>
</head>
<body>
  <div class="forgot-password-container">
    <h2>Forgot Password</h2>

    <?php if (!empty($message)): ?>
      <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if (!isset($_SESSION['otp_sent'])): ?>
      <form action="" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="generate_otp">Generate OTP</button>
      </form>
    <?php else: ?>
      <button onclick="sendOTP()">Send OTP via Email</button>
    <?php endif; ?>
  </div>
</body>
</html>
