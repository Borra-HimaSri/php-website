<?php
require 'vendor/autoload.php';
include 'db.php'; // $pdo from db.php

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && isset($_POST['name'])) {
    $name = $_POST['name'];
    $tmpFilePath = $_FILES['image']['tmp_name'];

    try {
        $uploadResult = $cloudinary->uploadApi()->upload($tmpFilePath);
        $imageUrl = $uploadResult['secure_url'];

        // Insert into PostgreSQL
        $stmt = $pdo->prepare("INSERT INTO teachers (name, image) VALUES (:name, :image)");
        $stmt->execute([
            ':name' => $name,
            ':image' => $imageUrl
        ]);

        echo "Image uploaded successfully!<br>";
        echo "<img src='" . htmlspecialchars($imageUrl) . "' width='300'>";
    } catch (Exception $e) {
        echo "Upload failed: " . $e->getMessage();
    }
} else {
?>
    <form method="POST" enctype="multipart/form-data">
        <label>Teacher Name:</label>
        <input type="text" name="name" required><br><br>
        <label>Select Image:</label>
        <input type="file" name="image" required><br><br>
        <button type="submit">Upload</button>
    </form>
<?php
}
?>
