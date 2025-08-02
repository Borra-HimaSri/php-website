<?php
// DO NOT ADD ANYTHING ABOVE THIS LINE! No spaces, no lines, no BOM.

include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header('Location: login.php');
    exit();
}

// Check DB connection
if ($conn->connect_error) {
    header("Location: db_error.php");
    exit();
    // OR log silently
    error_log("Database connection failed: " . $conn->connect_error);
    exit("Database connection failed.");
}

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>