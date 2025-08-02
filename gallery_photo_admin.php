<?php
ob_start();
include 'admin_common.php'; // Make sure this has the DB connection as $conn

require 'vendor/autoload.php';
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;


Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dyvs4ugkk',
        'api_key'    => '567619791139426',
        'api_secret' => 'ZmSo5zZoMgkr7LcGz_QHPRm7vVI'
    ],
    'url' => ['secure' => true]
]);

$cloudinary = new Cloudinary(Configuration::instance());



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $category = 'gallery-photo';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload = $cloudinary->uploadApi()->upload($_FILES['image']['tmp_name'], [
            'folder' => 'smartkids/gallery'
        ]);
        $imageUrl = $upload['secure_url'];

        $stmt = $conn->prepare("INSERT INTO images (image_path, category) VALUES (?, ?)");
        $stmt->bind_param("ss", $imageUrl, $category);
        if ($stmt->execute()) {
            header("Location: gallery_photo_admin.php");
            exit;
        } else {
            echo "Database insert failed!";
        }
        $stmt->close();
    } else {
        echo "Image upload failed!";
    }
}

if (isset($_POST['delete'])) {
    $imageId = intval($_POST['delete']);
    $stmt = $conn->prepare("SELECT image_path FROM images WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $stmt->bind_result($imagePathToDelete);
    if ($stmt->fetch()) {
        $stmt->close();
        $stmtDel = $conn->prepare("DELETE FROM images WHERE id = ?");
        $stmtDel->bind_param("i", $imageId);
        $stmtDel->execute();
        $stmtDel->close();
    } else {
        $stmt->close();
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
