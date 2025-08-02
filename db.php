<?php
$servername = "caboose.proxy.rlwy.net";
$port = 15095;
$dbname = "railway";
$username = "root";
$password = "HyUTQwwpDBYObcwdYsqGlHWyKPAlJbAz";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = mysqli_init();
$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
$conn->real_connect($servername, $username, $password, $dbname, $port);
?>
