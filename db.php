<?php
$servername = "sql210.infinityfree.com";  // Your actual MySQL hostname
$username = "if0_38329179";               // Your MySQL username
$password = "jPaaEZLNx5fn";               // Your MySQL password
$dbname = "if0_38329179_website";         // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
