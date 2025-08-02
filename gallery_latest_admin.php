<?php
ob_start();
include 'admin_common.php'; // Make sure this sets up $conn

require 'vendor/autoload.php';
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

// Cloudinary Configuration
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dyvs4ugkk',
        'api_key'    => '567619791139426',
        'api_secret' => 'ZmSo5zZoMgkr7LcGz_QHPRm7vVI'
    ],
    'url' => ['secure' => true]
]);

$cloudinary = new Cloudinary();

// Upload Image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $category = 'gallery-photo';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        try {
            $upload = $cloudinary->uploadApi()->upload($_FILES['image']['tmp_name'], [
                'folder' => 'smartkids/gallery'
            ]);
            $imageUrl = $upload['secure_url'];

            $stmt = $conn->prepare("INSERT INTO images (image_path, category) VALUES (?, ?)");
            $stmt->bind_param("ss", $imageUrl, $category);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: gallery_photo_admin.php");
                exit;
            } else {
                echo "Database insert failed!";
                $stmt->close();
            }
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

    $stmt = $conn->prepare("SELECT image_path FROM images WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $stmt->bind_result($imagePathToDelete);

    if ($stmt->fetch()) {
        $stmt->close();

        // Optional: Extract public ID from Cloudinary URL if you want to delete from Cloudinary too
        // $publicId = basename(parse_url($imagePathToDelete, PHP_URL_PATH), '.' . pathinfo($imagePathToDelete, PATHINFO_EXTENSION));
        // $cloudinary->uploadApi()->destroy('smartkids/gallery/' . $publicId);

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
    <title>Admin Gallery Latest</title>
    <link rel="stylesheet" href="css/news.css">
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



    <h2>Upload to Gallery Latest</h2>
    <form action="gallery_latest_admin.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="text" name="news_name" placeholder="News Title" required>
        <textarea name="news_description" placeholder="News Description" required></textarea>
        <input type="date" name="news_date" required>
        <button type="submit" name="upload">Upload Image</button>
    </form>

    <?php if (isset($editRow)) { ?>
        <h2>Edit News</h2>
        <form action="gallery_latest_admin.php" method="post">
            <input type="hidden" name="update" value="<?= $editRow['id'] ?>">
            <input type="text" name="news_name" value="<?= $editRow['news_name'] ?>" required>
            <textarea name="news_description" required><?= $editRow['news_description'] ?></textarea>
            <input type="date" name="news_date" value="<?= $editRow['news_date'] ?>" required>
            <button type="submit">Update News</button>
        </form>
    <?php } ?>


    <div class="gallery-container">
        <?php
        $result = $conn->query("SELECT * FROM images WHERE category='gallery-latest'");
        while ($row = $result->fetch_assoc()) {
            echo '<div>
                    <img src="' . $row['image_path'] . '" style="width: 150px; height: 150px; object-fit: cover;">
                    <form action="gallery_latest_admin.php" method="post">
                        <button type="submit" name="edit" value="' . $row['id'] . '">Edit</button>
                    </form>
                    <form action="gallery_latest_admin.php" method="post" onsubmit="return confirmDelete();">
                        <button type="submit" name="delete" value="' . $row['id'] . '">Delete</button>
                    </form>
                  </div>';
        }
        ?>
    </div>
</body>
</html>
