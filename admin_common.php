<?php
include 'db.php';
// Start session to manage user login status
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}



$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php'); // Redirect to login page after logout
    exit();
}
?>  