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

include("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $file_tmp = $_FILES['photo']['tmp_name'];
    $file_name = $_FILES['photo']['name'];

    try {
        $result = $cloudinary->uploadApi()->upload($file_tmp, [
            'folder' => 'smartkidsplayschool',
            'public_id' => pathinfo($file_name, PATHINFO_FILENAME),
            'overwrite' => true,
        ]);

        $image_url = $result['secure_url'];

        $sql = "INSERT INTO gallery_photos (image_url) VALUES ('$image_url')";
        if ($conn->query($sql) === TRUE) {
            echo "Photo uploaded and saved successfully.<br>";
        } else {
            echo "Database error: " . $conn->error;
        }
    } catch (Exception $e) {
        echo "Upload failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Upload Photo</title>
</head>
<body>
    <h2>Upload Gallery Photo</h2>
    <form action="gallery_photo_admin.php" method="post" enctype="multipart/form-data">
        <input type="file" name="photo" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Uploaded Photos</h3>
    <div style="display:flex; flex-wrap: wrap;">
        <?php
        $result = $conn->query("SELECT * FROM gallery_photos ORDER BY id DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<div style='margin:10px;'>
                    <img src='" . $row['image_url'] . "' style='width:200px; height:auto; border:1px solid #ccc; padding:5px;'>
                  </div>";
        }
        ?>
    </div>
</body>
</html>
