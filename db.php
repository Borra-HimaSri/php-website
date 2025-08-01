<?php
$servername = "caboose.proxy.rlwy.net";
$port = 15095;
$dbname = "railway";
$username = "root";
$password = "HyUTQwwpDBYObcwdYsqGlHWyKPAlJbAz";

// Optional: If you want to use $conn here too, include this:
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
