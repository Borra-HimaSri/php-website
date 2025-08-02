<?php
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dyvs4ugkk',
        'api_key'    => '567619791139426',
        'api_secret' => 'ZmSo5zZoMgkr7LcGz_QHPRm7vVI',
    ],
    'url' => [
        'secure' => true
    ]
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $tmpFilePath = $_FILES['image']['tmp_name'];
    $uploadResult = $cloudinary->uploadApi()->upload($tmpFilePath);

    echo "Image uploaded successfully!<br>";
    echo "<img src='" . $uploadResult['secure_url'] . "' width='300'>";
} else {
?>
    <form method="POST" enctype="multipart/form-data">
        <label>Select Image:</label>
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>
<?php
}
?>
