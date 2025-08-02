<?php
require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Cloudinary config
Configuration::instance([
  'cloud' => [
    'cloud_name' => 'dyvs4ugkk',
    'api_key'    => '567619791139426',
    'api_secret' => 'ZmSo5zZoMgkr7LcGz_QHPRm7vVI'],
  'url' => ['secure' => true]
]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Image to Cloudinary</title>
</head>
<body>
    <h2>Upload Image</h2>
    <form action="teachersadmin.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
        $tmpFile = $_FILES['image']['tmp_name'];

        try {
            $uploadResult = (new UploadApi())->upload($tmpFile);
            $imageUrl = $uploadResult['secure_url'];
            echo "<h3>Image uploaded successfully:</h3>";
            echo "<img src='$imageUrl' width='200'><br>";
            echo "<strong>URL:</strong> <a href='$imageUrl' target='_blank'>$imageUrl</a>";
        } catch (Exception $e) {
            echo "<p style='color:red;'>Upload failed: " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>
