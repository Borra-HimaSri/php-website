<?php
include 'db.php';
include 'admin_common.php';
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cloudinary config
Configuration::instance([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

// Upload teacher
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $name = $_POST['name'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $tmpFile = $_FILES["image"]["tmp_name"];

        try {
            $uploadResult = (new UploadApi())->upload($tmpFile, [
                'folder' => 'smartkids/teachers'
            ]);
            $imageUrl = $uploadResult['secure_url'];

            // Insert into PostgreSQL
            $stmt = $pdo->prepare("INSERT INTO teachers (name, image) VALUES (:name, :image)");
            $stmt->execute([
                ':name' => $name,
                ':image' => $imageUrl
            ]);
        } catch (Exception $e) {
            echo "<p style='color:red;'>Upload failed: " . $e->getMessage() . "</p>";
        }
    }
}

// Delete teacher
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get image URL (optional, if you want to delete from Cloudinary)
    $stmt = $pdo->prepare("SELECT image FROM teachers WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $image = $stmt->fetchColumn();

    // Delete from Cloudinary (optional)
    if ($image) {
        $parts = explode("/", parse_url($image, PHP_URL_PATH));
        $filenameWithExt = end($parts);
        $publicId = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $folder = 'smartkids/teachers/';
        $fullPublicId = $folder . $publicId;

        try {
            (new UploadApi())->destroy($fullPublicId);
        } catch (Exception $e) {
            echo "<p style='color:red;'>Cloudinary delete failed: " . $e->getMessage() . "</p>";
        }
    }

    // Delete from DB
    $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

// Fetch all teachers
$result = $pdo->query("SELECT * FROM teachers ORDER BY id ASC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Teachers</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/gallery.css">
    <style>
        body { font-family: Arial; margin: 30px; }
        .gallery-container { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .teacher-item { width: 100px; text-align: center; }
        .teacher-item img { width: 80px; height: 80px; object-fit: cover; border-radius: 50%; }
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
    <button type="submit" name="add_teacher">Add</button>
</form>

<h2>All Teachers</h2>
<div class="gallery-container">
    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="teacher-item">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Teacher">
            <br><br>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this teacher?')">
                <button>Delete</button>
            </a>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
