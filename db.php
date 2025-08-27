<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// PostgreSQL credentials from Render
$host = "dpg-d2mabb7diees73e61k3g-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "data_j4be";
$user = "data_j4be_user";
$password = "Bt0ZBgsg3RcPaytjpCI5WWmC0wTHm0Gs";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

use Cloudinary\Cloudinary;
// Cloudinary setup
$cloudinary = new Cloudinary([
    "cloud" => [
        "cloud_name" => $_ENV['CLOUDINARY_CLOUD_NAME'],
        "api_key"    => $_ENV['CLOUDINARY_API_KEY'],
        "api_secret" => $_ENV['CLOUDINARY_API_SECRET']
    ]
]);
?>
