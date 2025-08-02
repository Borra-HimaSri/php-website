<?php 

include 'admin_common.php';

// Include Cloudinary PHP SDK
require 'vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

// Cloudinary configuration
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dyvs4ugkk',
        'api_key'    => '567619791139426',
        'api_secret' => 'ZmSo5zZoMgkr7LcGz_QHPRm7vVI'
    ],
    'url' => [
        'secure' => true
    ]
]);

$cloudinary = new Cloudinary();

// Handle gallery-photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $category = 'gallery-photo';

    if (isset($_FILES['image'])) {
        $upload = $cloudinary->uploadApi()->upload($_FILES['image']['tmp_name'], [
            'folder' => 'smartkids/gallery'
        ]);
        $imageUrl = $upload['secure_url'];

        $sql = "INSERT INTO images (image_path, category) VALUES ('$imageUrl', '$category')";
        if ($conn->query($sql)) {
            header("Location: gallery_photo_admin.php");
            exit;
        } else {
            echo "Database insert failed!";
        }
    } else {
        echo "Image upload failed!";
    }
}

// Handle image deletion
if (isset($_POST['delete'])) {
    $imageId = $_POST['delete'];
    $result = $conn->query("SELECT image_path FROM images WHERE id = $imageId");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePathToDelete = $row['image_path'];
        $conn->query("DELETE FROM images WHERE id = $imageId");

        // Optionally, you can delete from Cloudinary if you store public_id
        // Not included here since we're storing only URL
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery Photo</title>
    <link rel="stylesheet" href="css/gallery.css">
    
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this image?");
        }
    </script>
</head>
<body>
<div class="admin-nav">
    <a href="gallery_photo_admin.php"><button>Gallery</button></a>
    <a href="gallery_event_admin.php"><button> Events</button></a>
    <a href="gallery_latest_admin.php"><button>News</button></a>
    <a href="admin.php"><button>Admin Page</button></a>
</div>

    <h2>Upload Photos to the Gallery Page</h2>
    <form action="gallery_photo_admin.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit" name="upload">Upload Image</button>
    </form>
  
    <div class="gallery-container">
        <?php
        $result = $conn->query("SELECT * FROM images WHERE category='gallery-photo'");
        while ($row = $result->fetch_assoc()) {
            echo '<div class="gallery-item">
                    <img src="' . $row['image_path'] . '" alt="Uploaded Image">
                    <form action="gallery_photo_admin.php" method="post" onsubmit="return confirmDelete();">
                        <button type="submit" name="delete" value="' . $row['id'] . '">Delete</button>
                    </form>
                  </div>';
        }
        ?>
    </div>
</body>
</html>
