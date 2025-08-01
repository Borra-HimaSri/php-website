<?php
$host = "caboose.proxy.rlwy.net";
$port = 15095;
$database = "railway";
$username = "root";
$password = "HyUTQwwpDBYObcwdYsqGlHWyKPAlJbAz";

$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
