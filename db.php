<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// PostgreSQL credentials from Render
$host = "dpg-d3bcq73uibrs73fcbq20-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "smartkid";
$user = "smartkid_user";
$password = "QUBkVzVuq81bLpSGQwyaontTyk0rntul";

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
