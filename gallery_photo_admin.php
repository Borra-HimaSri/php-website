<?php 

// Include common admin operations like session check, DB connection, and logout handling

include 'admin_common.php';

// Handle gallery-photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $imagePath = 'uploads/' . uniqid('img_', true) . '-' . basename($_FILES['image']['name']);
    $category = 'gallery-photo';

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        $sql = "INSERT INTO images (image_path, category) VALUES ('$imagePath', '$category')";
        if ($conn->query($sql)) {
            header("Location: gallery_photo_admin.php");
            exit;
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
        if (file_exists($imagePathToDelete)) {
            unlink($imagePathToDelete);
        }
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
