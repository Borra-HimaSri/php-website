<?php
ob_start();
include 'admin_common.php'; // $pdo is available
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

Configuration::instance([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

$cloudinary = new Cloudinary(Configuration::instance());

// Upload Image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $category = 'gallery-photo';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        try {
            $upload = $cloudinary->uploadApi()->upload($_FILES['image']['tmp_name'], [
                'folder' => 'smartkids/gallery'
            ]);
            $imageUrl = $upload['secure_url'];

            $stmt = $pdo->prepare("INSERT INTO images (image_path, category) VALUES (:image_path, :category)");
            $stmt->execute([
                ':image_path' => $imageUrl,
                ':category' => $category
            ]);

            header("Location: gallery_photo_admin.php");
            exit;
        } catch (Exception $e) {
            echo "Image upload error: " . $e->getMessage();
        }
    } else {
        echo "Image upload failed!";
    }
}

// Delete Image
if (isset($_POST['delete'])) {
    $imageId = intval($_POST['delete']);
    $stmt = $pdo->prepare("SELECT image_path FROM images WHERE id = :id");
    $stmt->execute([':id' => $imageId]);
    $imagePathToDelete = $stmt->fetchColumn();

    if ($imagePathToDelete) {
        // Extract public_id
        $parts = explode("/", parse_url($imagePathToDelete, PHP_URL_PATH));
        $filenameWithExt = end($parts);
        $publicId = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $folder = 'smartkids/gallery/';
        $fullPublicId = $folder . $publicId;

        // Delete from Cloudinary
        try {
            $cloudinary->uploadApi()->destroy($fullPublicId);
        } catch (Exception $e) {
            echo "Cloudinary delete failed: " . $e->getMessage();
        }

        // Delete from DB
        $stmtDel = $pdo->prepare("DELETE FROM images WHERE id = :id");
        $stmtDel->execute([':id' => $imageId]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery Photo</title>
    <link rel="icon" type="image/png" href="img/logo.png">
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
    <a href="gallery_event_admin.php"><button>Events</button></a>
    <a href="teachersadmin.php"><button>Teacher</button></a>
    <a href="admin.php"><button>Admin Page</button></a>
</div>

<h2>Upload Photos to the Gallery Page</h2>
<form action="gallery_photo_admin.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image" required>
    <button type="submit" name="upload">Upload Image</button>
</form>

<div class="gallery-container">
    <?php
    $stmt = $pdo->query("SELECT * FROM images WHERE category='gallery-photo'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="gallery-item">
                <img src="' . htmlspecialchars($row['image_path']) . '" alt="Uploaded Image">
                <form action="gallery_photo_admin.php" method="post" onsubmit="return confirmDelete();">
                    <button type="submit" name="delete" value="' . $row['id'] . '">Delete</button>
                </form>
              </div>';
    }
    ?>
</div>
</body>
</html>
