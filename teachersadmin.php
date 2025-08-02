<?php 
include 'db.php';

include 'admin_common.php';
require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cloudinary config
Configuration::instance([
  'cloud' => [
    'cloud_name' => 'dyvs4ugkk',
    'api_key'    => '567619791139426',
    'api_secret' => 'ZmSo5zZoMgkr7LcGz_QHPRm7vVI'],
  'url' => ['secure' => true]
]);

// Upload logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $tmpFile = $_FILES["image"]["tmp_name"];

        try {
            $uploadResult = (new UploadApi())->upload($tmpFile);
            $imageUrl = $uploadResult['secure_url']; // use full Cloudinary URL

            // Insert name and image URL into DB
            $stmt = $conn->prepare("INSERT INTO teachers (name, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $imageUrl);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            echo "<p style='color:red;'>Upload failed: " . $e->getMessage() . "</p>";
        }
    }
}

// Delete logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT image FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch teachers
$result = $conn->query("SELECT * FROM teachers ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Team</title>
     <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/gallery.css">
    <style>
        body { font-family: Arial; margin: 30px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        img { height: 60px; }
    </style>
</head>
<body>
<div class="admin-nav">
    <a href="gallery_photo_admin.php"><button>Gallery</button></a>
    <a href="gallery_event_admin.php"><button>Events</button></a>
    <a href="teachersadmin.php"><button>Teacher</button></a>
    <a href="admin.php"><button>Admin Page</button></a>
</div>

<h2>Add Teacher</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Teacher name" required>
    <input type="file" name="image" required>
    <button type="submit">Add</button>
</form>

<h2>All Teachers</h2>

<div style="display: flex; flex-wrap: wrap; gap: 20px;">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div style="width: 100px; text-align: center;">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Teacher" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"><br><br>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this teacher?')">
                <button>Delete</button>
            </a>
        </div>
    <?php endwhile; ?>
</div>


</body>
</html>
